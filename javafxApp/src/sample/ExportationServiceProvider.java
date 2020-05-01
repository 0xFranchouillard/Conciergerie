package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationServiceProvider {

    CheckBox providerID = new CheckBox("Id du prestatire");
    CheckBox agency = new CheckBox("Agence");
    CheckBox lastName = new CheckBox("Nom de Famille");
    CheckBox firstName = new CheckBox("Prénom");
    CheckBox email = new CheckBox("Email");
    CheckBox city = new CheckBox("Ville");
    CheckBox address = new CheckBox("Adresse");
    CheckBox phoneNumber = new CheckBox("Numero de téléphone");
    CheckBox qrCode = new CheckBox("QR Code");


    public VBox exportationServiceProvider(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox(informationLabel,providerID,agency,lastName,firstName,email,city,address,phoneNumber,qrCode,
                hBox);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineServiceProvider(){
        String containCommand = "SELECT ";

        if(providerID.isSelected()){
            containCommand += "providerID, ";
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
        if (qrCode.isSelected()){
            containCommand += "qrCode, ";
        }

        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM ServiceProvider";
        return containCommand;
    }
}
