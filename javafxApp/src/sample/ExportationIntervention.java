package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationIntervention {

    CheckBox interventionID = new CheckBox("Id de l'intervation");
    CheckBox dateIntervention = new CheckBox("Date de l'intervention");
    CheckBox timeIntervention = new CheckBox("heure de l'intervention");
    CheckBox pastType= new CheckBox("Type");
    CheckBox statutIntervention = new CheckBox("Statut de l'intervention");
    CheckBox clientId = new CheckBox("Id du client");
    CheckBox agency = new CheckBox("Agence client");
    CheckBox serviceID = new CheckBox("id du service");
    CheckBox providerID = new CheckBox("Id prestataire");
    CheckBox agencyProvider = new CheckBox("Agence prestataire");

    public VBox exportationIntervention(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox(informationLabel,interventionID,dateIntervention, timeIntervention,pastType,statutIntervention,
                clientId,agency,serviceID,providerID,agencyProvider
                ,hBox);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineIntervention(){
        String containCommand = "SELECT ";

        if(interventionID.isSelected()){
            containCommand += "interventionID, ";
        }
        if (dateIntervention.isSelected()){
            containCommand += "dateIntervention, ";
        }
        if (timeIntervention.isSelected()){
            containCommand += "timeIntervention, ";
        }
        if (pastType.isSelected()){
            containCommand += "pastType, ";
        }
        if (statutIntervention.isSelected()){
            containCommand += "statutIntervention, ";
        }
        if (clientId.isSelected()){
            containCommand += "clientId, ";
        }
        if (agency.isSelected()){
            containCommand += "agency, ";
        }
        if (serviceID.isSelected()){
            containCommand += "serviceID, ";
        }
        if (providerID.isSelected()){
            containCommand += "providerID, ";
        }
        if (agencyProvider.isSelected()){
            containCommand += "agencyProvider, ";
        }


        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM intervention";
        System.out.println(containCommand);
        return containCommand;
    }
}
