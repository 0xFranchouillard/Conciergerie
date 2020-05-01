package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationSubscribes {

    CheckBox subscriptionDate = new CheckBox("Date d'abonnement");
    CheckBox endDate = new CheckBox("Date de fin");
    CheckBox recurrence = new CheckBox("Récurrence");
    CheckBox month = new CheckBox("Mois");
    CheckBox valueMonth = new CheckBox("Heure au mois");  ////?.....
    CheckBox clientID = new CheckBox("Id client");
    CheckBox agency	 = new CheckBox("Agence");
    CheckBox subscriptionID = new CheckBox("Id de l'abonnement");


    public VBox exportationSubscribes(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox(informationLabel,subscriptionDate, endDate,recurrence,month,valueMonth,clientID,agency,subscriptionID,
                hBox);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineSubscribes(){
        String containCommand = "SELECT ";

        if(subscriptionDate.isSelected()){
            containCommand += "subscriptionDate, ";
        }
        if (endDate.isSelected()){
            containCommand += "endDate, ";
        }
        if (recurrence.isSelected()){
            containCommand += "recurrence, ";
        }
        if (month.isSelected()){
            containCommand += "month, ";
        }
        if (valueMonth.isSelected()){
            containCommand += "valueMonth, ";
        }
        if (clientID.isSelected()){
            containCommand += "clientID, ";
        }
        if (agency.isSelected()){
            containCommand += "agency, ";
        }
        if (subscriptionID.isSelected()){
            containCommand += "subscriptionID, ";
        }


        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM subscribes";
        return containCommand;
    }

}
