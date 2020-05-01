package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.layout.VBox;

public class ExportationActivity {

    CheckBox activityID = new CheckBox("Id de l'activité");
    CheckBox professionName = new CheckBox("Nom de la profession");
    CheckBox contract = new CheckBox("Contrat");
    CheckBox providerID = new CheckBox("Id du prestataire");
    CheckBox agency = new CheckBox("Agence");

    public VBox exportationActivity(Button execute, Button returnMainScene){

        VBox view = new VBox( activityID,professionName,contract,providerID, agency,execute,returnMainScene);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineActivity(){
        String containCommand = "SELECT ";

        if(activityID.isSelected()){
            containCommand += "activityID, ";
        }
        if (professionName.isSelected()){
            containCommand += "professionName, ";
        }
        if (contract.isSelected()){
            containCommand += "contract, ";
        }
        if (providerID.isSelected()){
            containCommand += "providerID, ";
        }
        if (agency.isSelected()){
            containCommand += "agency, ";
        }

        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " From activity";
        System.out.println(containCommand);
        return containCommand;
    }
}
