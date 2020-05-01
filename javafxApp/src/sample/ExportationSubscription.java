package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationSubscription {

    CheckBox idSubscription = new CheckBox("ID Abonnement");
    CheckBox language = new CheckBox("Langue");
    CheckBox nameSubscription = new CheckBox("Nom Abonnement");
    CheckBox nbDays = new CheckBox("Nombre de jour");
    CheckBox startTime = new CheckBox("Heure de debut");
    CheckBox endTime = new CheckBox("Heure de fin");
    CheckBox pricePerYear = new CheckBox("Prix par an");
    CheckBox value = new CheckBox("Valeur"); ////?.....

    public VBox exportationSubscription(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox(informationLabel,idSubscription,language,nbDays, startTime, endTime,pricePerYear,value,
               hBox);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineSubscription(){
        String containCommand = "SELECT ";

        if(idSubscription.isSelected()){
            containCommand += "subscriptionID, ";
        }
        if(language.isSelected()){
            containCommand += "language, ";
        }
        if (nameSubscription.isSelected()){
            containCommand += "nameSubscription, ";
        }
        if (nbDays.isSelected()){
            containCommand += "nbDays, ";
        }
        if (startTime.isSelected()){
            containCommand += "startTime, ";
        }
        if (endTime.isSelected()){
            containCommand += "endTime, ";
        }
        if (pricePerYear.isSelected()){
            containCommand += "pricePerYear, ";
        }
        if (value.isSelected()){
            containCommand += "value, ";
        }


        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM Subscription";
        return containCommand;
    }

}
