package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.layout.VBox;

public class ExportationPlanning {

    CheckBox planningID = new CheckBox("Id du planning");
    CheckBox datePlanning = new CheckBox("Date de planning");
    CheckBox hourPlanning = new CheckBox("Heure");
    CheckBox statutPlanning = new CheckBox("Statut du planning");
    CheckBox providerId = new CheckBox("Id du prestataire");
    CheckBox agency = new CheckBox("Agence");
    CheckBox interventionID = new CheckBox("Id de l'intervention");

    public VBox exportationPlanning(Button execute, Button returnMainScene){

        VBox view = new VBox( planningID, datePlanning, hourPlanning,statutPlanning, providerId,agency,interventionID ,execute,returnMainScene);
        view.setSpacing(15);
        return view;
    }

    public String sqlLinePlanning(){
        String containCommand = "SELECT ";

        if(planningID.isSelected()){
            containCommand += "planningID, ";
        }
        if (datePlanning.isSelected()){
            containCommand += "datePlanning, ";
        }
        if (hourPlanning.isSelected()){
            containCommand += "hourPlanning, ";
        }
        if (statutPlanning.isSelected()){
            containCommand += "statutPlanning, ";
        }
        if (providerId.isSelected()){
            containCommand += "providerId, ";
        }
        if (agency.isSelected()){
            containCommand += "agency, ";
        }
        if (interventionID.isSelected()){
            containCommand += "interventionID, ";
        }

        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " From planning";
        System.out.println(containCommand);
        return containCommand;
    }

}
