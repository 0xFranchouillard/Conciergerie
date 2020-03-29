package LuxuryService.projet;

import java.net.*;
import java.io.*;
import java.util.ArrayList;
import java.util.Arrays;

public class GetUrlComponent {

    String delimOnePersonSplit = Character.toString((char)34);// correspond au guillemets
    String delimPersonSplit = Character.toString((char)125);// correspond aux crochets


    public ArrayList<String> getContent(String name) throws IOException {

        URL urlName = new URL("https://google.com");

        String[] personSplit; //split des personnes
        ArrayList<String> onePersonSplit = new ArrayList<String>();

        

        if(name == "ListePrestataireParisien" ){

            urlName = new URL("http://localhost/conciergerie/API_TEST_URI/v1/prestataire/paris");
        }
        else if (name == "ListePrestataireTotal" ){

            urlName = new URL("http://localhost/conciergerie/API_TEST_URI/v1/prestataire");
        }
        else if (name == "ListeClientTotal" ){

            urlName = new URL("http://localhost/conciergerie/API_TEST_URI/v1/client");
        }
        else if (name == "Bareme" ){

            urlName = new URL("http://localhost/conciergerie/API_TEST_URI/v1/client/");
        }
        else if (name == "ListeClientParis" ){

            urlName = new URL("http://localhost/conciergerie/API_TEST_URI/v1/client/paris");
        }


        try (BufferedReader reader = new BufferedReader(new InputStreamReader(urlName.openStream(), "UTF-8"))) {

            //StringBuilder content = new StringBuilder();
            String content = "";

            for (String line; (line = reader.readLine()) != null;) {
                //System.out.println(line);
                content = content + line;
            }

            personSplit = content.split(delimPersonSplit);


            //onePersonSplit = content.split(delimPersonSplit);

            for (int i = 0; i < personSplit.length ; i++) {

                onePersonSplit.add( (Arrays.toString(personSplit[i].split(delimOnePersonSplit))));
            }


            return onePersonSplit;
        }

    }


}
