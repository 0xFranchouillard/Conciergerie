package sample;

import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class ExportationBill {

    CheckBox billID = new CheckBox("Id facture");
    CheckBox estimate = new CheckBox("Estimation");
    CheckBox totalPrice = new CheckBox("Montant total");
    CheckBox priceService = new CheckBox("Prix du service");
    CheckBox numberTaken = new CheckBox("Nombre choisie");
    CheckBox billDate = new CheckBox("Date de la facture");
    CheckBox validityDate = new CheckBox("Date de validité");
    CheckBox clientID = new CheckBox("id du client");
    CheckBox agency = new CheckBox("agence");
    CheckBox serviceId = new CheckBox("Id du service");

    public VBox exportationBill(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox hBox = new HBox(choiceExport,execute, printResult,returnMainScene);
        hBox.setSpacing(20);

        VBox view = new VBox( informationLabel,billID,estimate,totalPrice,priceService,numberTaken,billDate,validityDate,
                clientID ,agency,serviceId,
                hBox);
        view.setSpacing(15);
        return view;
    }

    public String sqlLineBill(){
        String containCommand = "SELECT ";

        if(billID.isSelected()){
            containCommand += "billID, ";
        }
        if(estimate.isSelected()){
            containCommand += "estimate, ";
        }
        if (totalPrice.isSelected()){
            containCommand += "totalPrice, ";
        }
        if(priceService.isSelected()){
            containCommand += "priceService, ";
        }
        if(numberTaken.isSelected()){
            containCommand += "numberTaken, ";
        }
        if (billDate.isSelected()){
            containCommand += "billDate, ";
        }
        if(validityDate.isSelected()){
            containCommand += "validityDate, ";
        }
        if (clientID .isSelected()){
            containCommand += "clientID, ";
        }
        if (agency.isSelected()){
            containCommand += "agency, ";
        }
        if(serviceId.isSelected()){
            containCommand += "serviceId, ";
        }


        containCommand = containCommand.substring(0,containCommand.length() -2);
        containCommand += " FROM bill";
        return containCommand;
    }


}
