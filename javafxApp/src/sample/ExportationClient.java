package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationClient {

    CheckBox clientID = new CheckBox("Id client");
    CheckBox agency = new CheckBox("agence");
    CheckBox lastName = new CheckBox("Nom de famille");
    CheckBox firstName = new CheckBox("Prénom");
    CheckBox email = new CheckBox("email");
    CheckBox city = new CheckBox("Ville");
    CheckBox address = new CheckBox("Adresse");
    CheckBox phoneNumber = new CheckBox("Numéro de téléphone");


    public VBox exportationClient(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox(informationLabel,clientID,agency,lastName,firstName,email,city,address,phoneNumber,
                hBox);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineClient(){
        String containCommand = "SELECT ";

        if(clientID.isSelected()){
            containCommand += "clientID, ";
        }
        if (agency.isSelected()){
            containCommand += "agency, ";
        }
        if (lastName.isSelected()){
            containCommand += "lastName, ";
        }
        if (firstName.isSelected()){
            containCommand += "firstName, ";
        }
        if (email.isSelected()){
            containCommand += "email, ";
        }
        if (city.isSelected()){
            containCommand += "city, ";
        }
        if (address.isSelected()){
            containCommand += "address, ";
        }
        if (phoneNumber.isSelected()){
            containCommand += "phoneNumber, ";
        }

        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM client";
        return containCommand;
    }

}
