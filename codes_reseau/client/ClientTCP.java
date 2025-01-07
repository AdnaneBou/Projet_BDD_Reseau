package client;

import java.io.*;
import java.net.*;
import java.util.Properties;
import java.util.Scanner;

public class ClientTCP {
    private static String serverHostname = "127.0.0.1";  // Adresse par défaut
    private static int serverPort = 12345;               // Port par défaut
    private static int timeout = 25000;                   // Timeout de connexion en ms

    public static void main(String[] args) {
        // Charger les paramètres depuis un fichier de configuration (facultatif)
        // loadConfiguration();

        try (Socket socket = new Socket()) {
            // Connexion au serveur avec un timeout
            socket.connect(new InetSocketAddress(serverHostname, serverPort), timeout);
            System.out.println("Connecté au serveur sur " + serverHostname + ":" + serverPort);

            // Configuration du timeout de lecture
            socket.setSoTimeout(timeout);

            // Flux pour envoyer et recevoir des données
            PrintWriter writer = new PrintWriter(socket.getOutputStream(), true);
            BufferedReader reader = new BufferedReader(new InputStreamReader(socket.getInputStream()));
            Scanner scanner = new Scanner(System.in);

            // Étape 1 : Envoi de l'ID du lecteur
            System.out.print("Entrez l'ID du lecteur : ");
            String idLecteur = scanner.nextLine();
            writer.println("LECTEUR:" + idLecteur);

            // Vérifier la réponse du serveur pour l'ID du lecteur
            String response = reader.readLine();
            System.out.println("Réponse reçue du serveur : " + response);
            if (!"ID Lecteur valide".equals(response)) {
                System.out.println("Erreur : " + response);
                return; // Stopper le client si l'ID lecteur est invalide
            }
            System.out.println("Le lecteur est valide.");

            // Étape 2 : Envoi du numéro de carte
            System.out.print("Entrez le numéro de carte : ");
            String numeroCarte = scanner.nextLine();
            writer.println("CARTE:" + numeroCarte);

            // Vérifier la réponse du serveur pour le numéro de carte
            response = reader.readLine();
            if (!"Carte valide".equals(response)) {
                System.out.println("Erreur : " + response);
                return; // Stopper le client si la carte est invalide
            }
            System.out.println("La carte est valide.");

            // Étape 3 : Envoi du numéro étudiant
            System.out.print("Entrez le numéro étudiant : ");
            String numeroEtudiant = scanner.nextLine();
            writer.println("ETUDIANT:" + numeroEtudiant);

            // Vérifier la réponse du serveur pour le numéro étudiant
            response = reader.readLine();
            if (!"Numero etudiant valide".equals(response)) {
                System.out.println("Erreur : " + response);
                return; // Stopper le client si le numéro étudiant est invalide
            }
            System.out.println("L'étudiant est valide.");

            // Lecture de la réponse finale après l'enregistrement de la présence
            response = reader.readLine();
            if ("Presence enregistree".equals(response)) {
                System.out.println("Présence de l'étudiant enregistrée avec succès.");
            }
            if ("Presence deja enregistree".equals(response)) {
                System.out.println("L'étudiant a déjà été noté comme présent.");
            }
            if ("Etudiant inconnu".equals(response)) {
                System.out.println("L'étudiant n'est pas inscrit à ce cours/formation.");
            }

            System.out.println("Connexion terminée.");

        } catch (SocketTimeoutException e) {
            System.err.println("Erreur : Le serveur n'a pas répondu dans le délai imparti.");
        } catch (UnknownHostException e) {
            System.err.println("Erreur : Hôte inconnu - " + serverHostname);
        } catch (ConnectException e) {
            System.err.println("Erreur : Connexion refusée sur " + serverHostname + ":" + serverPort);
        } catch (IOException e) {
            System.err.println("Erreur d'E/S lors de la connexion à " + serverHostname + ":" + serverPort);
        }
    }


    // Fonction de validation du message avant l'envoi (si nécessaire)
    private static boolean validateMessage(String message) {
        if (message == null || message.length() > 1024) {
            System.err.println("Erreur : Le message est trop long ou invalide.");
            return false;
        }
        // Ajoutez des vérifications spécifiques si nécessaire
        return true;
    }
}
