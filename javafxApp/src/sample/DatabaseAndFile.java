package sample;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.Date;

public class DatabaseAndFile {

    String fileName = "";
    String result = "";

    public void connectSQl(String query, String name, String exportFormat,Boolean wantToPrint) {

        CreatingPdf creatingPdf = new CreatingPdf();

        try {

            // connection with the database
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection(
                    "jdbc:mysql://localhost:3306/luxuryservices?autoReconnect=true&useSSL=false", "root", "");

            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery(query);
            ResultSetMetaData metaData = rs.getMetaData(); // get column name
            int maxColumn = metaData.getColumnCount(); // get number of column
            ////

            if(!wantToPrint){

                switch (exportFormat){
                    case "TXT":
                        //txt file
                        createFile(name, ".txt");
                        getStringQuery(rs,metaData,maxColumn);
                        writeTxt();
                        break;

                    case "PDF":
                        //PDF FILE
                        createFile(name,".pdf");
                        getStringQuery(rs,metaData,maxColumn);
                        creatingPdf.createPdf(fileName,result);
                }
            }
            else{
                getStringQuery(rs, metaData, maxColumn);
            }

           con.close();

        } catch (Exception e) {
            System.out.println(e);
        }
    }

    private void writeTxt() throws IOException {

        FileWriter myWriter = new FileWriter(fileName);
        myWriter.write(result);
        myWriter.close();

    }

    private void createFile(String name, String type){

        String pattern = "dd-MM-yyyy";
        SimpleDateFormat simpleDateFormat = new SimpleDateFormat(pattern);

        String date = simpleDateFormat.format(new Date());

        fileName = name + "-" + date + type;

        if (type == ".txt") {
            try{
                File file = new File(fileName);
                if (file.createNewFile()) {
                    System.out.println("File created: " + file.getName());
                } else {
                    System.out.println("File already exists.");
                }
            } catch (IOException e) {
                System.out.println("An error occurred.");
                e.printStackTrace();
            }
        }

    }

    private void getStringQuery(ResultSet rs, ResultSetMetaData metaData,int maxColumn) throws SQLException {

        result = "";

        while (rs.next()){
            for (int j = 1; j < maxColumn + 1; j++) {
                result += (metaData.getColumnLabel(j)+ ":" + rs.getString(j) + "  ");
            }
            result += (Character.toString((char)10)); //rajoute un retour à la ligne
        }
    }

    public String getResult() {
        return result;
    }
}

