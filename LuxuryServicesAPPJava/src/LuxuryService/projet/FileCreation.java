package LuxuryService.projet;

import java.io.File;  // Import the File class
import java.io.FileWriter;   // Import the FileWriter class
import java.io.IOException;  // Import the IOException class to handle errors
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

public class FileCreation {

    GetUrlComponent url = new GetUrlComponent();

    String pattern = "dd-MM-yyyy";
    SimpleDateFormat simpleDateFormat = new SimpleDateFormat(pattern);

    String date = simpleDateFormat.format(new Date());

       public void createFile(String input){
           String fileName;

           fileName = input + "-" + date +".txt";

           try{
               File file = new File(fileName);
               if (file.createNewFile()) {
                   System.out.println("File created: " + file.getName());
                   writeFile(fileName, input);
               } else {
                   System.out.println("File already exists.");
               }
           } catch (IOException e) {
               System.out.println("An error occurred.");
               e.printStackTrace();
           }


       }

       public void writeFile(String fileName, String input){

           try {
               FileWriter myWriter = new FileWriter(fileName);
               ArrayList<String>  content = new ArrayList<>();
               String convert;

               content = url.getContent(input);

               for (int i = 0; i < content.size() ; i++) {

                   convert = content.get(i).replace(",","")  ;
                   convert = convert.replace(Character.toString((char)91),""); // enleve le [
                   convert = convert.replace(Character.toString((char)93),""); // enleve le ]
                   convert = convert.replace(Character.toString((char)123),"");// enleve le {

                   myWriter.write( convert );
                   myWriter.write(Character.toString((char)10)); //rajoute un retour à la ligne
               }

               myWriter.close();
               System.out.println("Successfully wrote to the file.");


           } catch (IOException e) {
               System.out.println("An error occurred.");
               e.printStackTrace();
           }
       }

}
