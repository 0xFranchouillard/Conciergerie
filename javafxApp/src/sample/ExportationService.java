package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationService {

    CheckBox serviceID = new CheckBox("Id du service");
    CheckBox language = new CheckBox("Langage");
    CheckBox nameService = new CheckBox("Nom du service");
    CheckBox description = new CheckBox("Description");
    CheckBox priceService = new CheckBox("Prix service");
    CheckBox priceRecurrentService = new CheckBox("Prix service recurrent");
    CheckBox priceTypeService = new CheckBox("prix Type service");  ////?.....
    CheckBox minimumType = new CheckBox("Type minimum");  ////?.....

    public VBox exportationService(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox(informationLabel,serviceID,language,nameService,description,priceService,
                priceRecurrentService,priceTypeService,minimumType,
                hBox);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineService(){
        String containCommand = "SELECT ";

        if(serviceID.isSelected()){
            containCommand += "serviceID, ";
        }
        if (language.isSelected()){
            containCommand += "language, ";
        }
        if (nameService.isSelected()){
            containCommand += "nameService, ";
        }
        if (description.isSelected()){
            containCommand += "description, ";
        }
        if (priceService.isSelected()){
            containCommand += "priceService, ";
        }
        if (priceRecurrentService.isSelected()){
            containCommand += "priceRecurrentService, ";
        }
        if (priceTypeService.isSelected()){
            containCommand += "priceTypeService, ";
        }
        if (minimumType.isSelected()){
            containCommand += "minimumType, ";
        }

        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM Service";
        System.out.println(containCommand);
        return containCommand;
    }



}
