package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationTariff {

    CheckBox tariffID = new CheckBox("Id du tarif");
    CheckBox priceService = new CheckBox("Prix du service");
    CheckBox contract = new CheckBox("Contrat");
    CheckBox serviceID = new CheckBox("Id du service");
    CheckBox providerID = new CheckBox("Id du prestataire");
    CheckBox agency = new CheckBox("Agence");
    CheckBox dateID = new CheckBox("Jour travaillé");


    public VBox exportationTariff(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox(informationLabel,tariffID,priceService,dateID,
                contract,serviceID,providerID,agency,hBox);

        view.setSpacing(15);
        return view;
    }

    public String sqlLineTraiff(){
        String containCommand = "SELECT ";

        if(tariffID.isSelected()){
            containCommand += "tariffID, ";
        }
        if (priceService.isSelected()){
            containCommand += "priceService, ";
        }
        if (contract.isSelected()){
            containCommand += "contract, ";
        }
        if (serviceID.isSelected()){
            containCommand += "serviceID, ";
        }
        if (providerID.isSelected()){
            containCommand += "providerID, ";
        }
        if (agency.isSelected()){
            containCommand += "agency, ";
        }
        if (dateID.isSelected()){
            containCommand += "dateID, ";
        }

        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM Tariff";
        System.out.println(containCommand);
        return containCommand;
    }
}
