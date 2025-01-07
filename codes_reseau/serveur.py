import os
import socket
import logging
import sys
import time
import threading
import psycopg2  # Pour PostgreSQL
import configparser  # Pour lire le fichier de configuration



# AVERTISSEMENT
# !!!!!!!
# Il faut installer psycopg2 sur le TERMINAL au PREALABLE avec ceci:  pip install psycopg2
# !!!!!!!
# AVERTISSEMENT



# Configurer le logging pour des traces de débogage
logging.basicConfig(level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')

# Lire le fichier de configuration
config = configparser.ConfigParser()
config.read('config.ini', encoding='utf-8')


# Charger les paramètres du serveur depuis le fichier de configuration
# HOST = config.get('server', 'host', fallback='127.0.0.1')
# PORT = config.getint('server', 'port', fallback=12345)

# Charger les paramètres de la base de données depuis le fichier de configuration
DB_HOST = config.get('database', 'db_host')
DB_NAME = config.get('database', 'db_name')
DB_USER = config.get('database', 'db_user')
DB_PASSWORD = config.get('database', 'db_password')

# Paramètres du serveur (configurables)
HOST = '127.0.0.1'
PORT = 12345

# Connexion à la base de données PostgreSQL
def connect_to_database():
    try:
        # Connexion PostgreSQL
        conn = psycopg2.connect(
            host=DB_HOST,
            database=DB_NAME,
            user=DB_USER,
            password=DB_PASSWORD
        )
        logging.info("Connexion a la base de donnees PostgreSQL etablie.")
        return conn
    except psycopg2.Error as e:
        logging.error(f"Erreur de connexion a la base de donnees : {e}")
        sys.exit(1)


# Vérification de l'ID du lecteur
def verifier_lecteur(db_conn, id_lecteur):
    with db_conn.cursor() as cur:
        cur.execute("SELECT * FROM lecteurs WHERE id_lecteur = %s", (id_lecteur,))
        return cur.fetchone() is not None

# Vérification de la carte : si elle existe dans la BDD de l'université ou non
def verifier_carte(db_conn, numero_carte):
    with db_conn.cursor() as cur:
        cur.execute("SELECT * FROM etudiants WHERE numero_carte_etudiant = %s", (numero_carte,))
        return cur.fetchone() is not None

# Vérification du numéro d'étudiant : si elle existe dans la BDD de l'université ou non
def verifier_etudiant(db_conn, numero_etudiant):
    with db_conn.cursor() as cur:
        cur.execute("SELECT * FROM etudiants WHERE id_persn_etudiant = %s", (numero_etudiant,))
        return cur.fetchone() is not None

# Enregistrement de la présence de l'étudiant : enregistre la présence de l'étudiant si ce n'est pas encore fait/ s'il y est inscrit 
def enregistrer_presence(db_conn, numero_etudiant):
    with db_conn.cursor() as cur:
        # Vérifier si l'étudiant est déjà noté comme présent
        cur.execute("SELECT statut_etudiant FROM assister WHERE id_persn_etudiant = %s", (numero_etudiant,))
        result = cur.fetchone()
        res = "erreur"
        if result and result[0] == "present":
            # L'étudiant est déjà noté présent
            print("L'étudiant est déjà noté comme présent.")
            res = "deja enregistree"
        elif result and result[0] == "absent":
            # Insérer l'heure de passage
            cur.execute("UPDATE assister SET heure_arriver = NOW() WHERE id_persn_etudiant= %s", (numero_etudiant,))
            # Mettre à jour le statut de présence dans la table `assister`
            cur.execute("UPDATE assister SET statut_etudiant = 'present' WHERE id_persn_etudiant = %s", (numero_etudiant,))

            db_conn.commit()
            print("Presence enregistree pour l'etudiant.")
            res = "non enregistree"
        else:
            # l'étudiant n'est pas inscrit à ce cours
            print("L'etudiant n'est pas inscrit a ce cours/formation.")
            res = "etudiant inconnu"
        return res


# Fonction principale pour traiter les connexions clients
def handle_client(conn, addr, db_conn):
    logging.info(f"Client connecte : {addr}")
    conn.settimeout(300)  # Timeout pour gérer un client lent
    
    try:
        while True:
            try:
                data = conn.recv(4096)
                
                # Déconnexion client
                if not data:
                    logging.info(f"Deconnexion du client {addr}")
                    break
                
                # Vérification de la taille et contenu des données
                if len(data) > 1024:
                    logging.warning(f"Depassement de taille de buffer de {addr}")
                    conn.sendall(b"Erreur: taille de buffer depassee.\n")
                    continue
                

                # Décodage et nettoyage pour éviter injection SQL
                message = data.decode('utf-8').strip()
                logging.debug(f"Message recu de {addr} : {message}")

                # Étape 1: Vérification du lecteur
                if message.startswith("LECTEUR"):
                    id_lecteur = message.split(":")[1].strip()
                    if verifier_lecteur(db_conn, id_lecteur):
                        conn.sendall(b"ID Lecteur valide\n")
                    else:
                        conn.sendall(b"Lecteur de carte invalide\n")
                        return
                    
                # Étape 2: Vérification de la carte
                elif message.startswith("CARTE"):
                    numero_carte = message.split(":")[1].strip()
                    if verifier_carte(db_conn, numero_carte):
                        conn.sendall(b"Carte valide\n")
                    else:
                        conn.sendall(b"Carte invalide\n")
                        return

                # Étape 3: Vérification du numéro étudiant
                elif message.startswith("ETUDIANT"):
                    numero_etudiant = message.split(":")[1].strip()
                    if verifier_etudiant(db_conn, numero_etudiant):
                        conn.sendall(b"Numero etudiant valide\n")
        
                        # Étape 4: Essayer d'enregistrer la présence
                        presence_enregistree = enregistrer_presence(db_conn, numero_etudiant)
                        if presence_enregistree == "non enregistree":
                            conn.sendall(b"Presence enregistree\n")
                        elif presence_enregistree == "deja enregistree":
                            conn.sendall(b"Presence deja enregistree\n")
                        else :
                            conn.sendall(b"Etudiant inconnu\n")
                    else:
                        conn.sendall(b"Numero etudiant invalide\n")


                # Sinon, message inconnu
                else:
                    conn.sendall(b"Commande inconnue\n")
                
            except socket.timeout:
                logging.warning(f"Client {addr} ne repond pas.")
                conn.sendall(b"Erreur: delai d'attente depasse.\n")
                break
            except (ConnectionResetError, BrokenPipeError):
                logging.warning(f"Connexion avec le client {addr} perdue brutalement.")
                break
            except Exception as e:
                logging.error(f"Erreur lors de la reception de donnees de {addr} : {e}")
                conn.sendall(b"Erreur de serveur.\n")
                break
    finally:
        conn.close()
        logging.info(f"Connexion fermee pour le client {addr}")


# Lancement du serveur avec gestion des erreurs de réseau
def start_server(host, port):
    # Essayer de se lier au port, et gérer l’erreur s'il est occupé
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        try:
            s.bind((host, port))
        except socket.error as e:
            logging.error(f"Erreur lors du bind du socket : {e}")
            sys.exit(1)
        
        s.listen()
        logging.info(f"Serveur en ecoute sur {host}:{port}...")
        
        db_conn = connect_to_database()   # Connexion a la base de donnees
        
        while True:
            try:
                conn, addr = s.accept()  # Accepter une nouvelle connexion
                client_thread = threading.Thread(target=handle_client, args=(conn, addr, db_conn))
                client_thread.start()
            except Exception as e:
                logging.error(f"Erreur lors de l'acceptation de la connexion : {e}")
            except KeyboardInterrupt:
                logging.info("Arret du serveur...")
                break
        db_conn.close()  # Ferme la connexion à la base de données lorsque le serveur s'arrête

# Execution du serveur
if __name__ == "__main__":
    start_server(HOST, PORT)
