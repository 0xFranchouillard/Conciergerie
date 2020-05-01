package sample;

import javafx.scene.control.Button;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class FindInformation {

    ChoiceBox choiceObjectToFind = new ChoiceBox();
    ChoiceBox choiceRowToFind = new ChoiceBox();
    TextField valueUserWrite = new TextField();
    Button validObject = new Button("Choisir objet");
    String objectPrint = "";
    String objectDB = "";

    public VBox findInfo(Label informationLabel, Button execute, ChoiceBox choiceExport, Button returnMainScene, Button printResult){

        HBox bottomHBox = new HBox(validObject,choiceExport,execute, printResult,returnMainScene);
        bottomHBox.setSpacing(20);

        HBox midHBox = new HBox(choiceObjectToFind,validObject,choiceRowToFind,valueUserWrite);
        midHBox.setSpacing(20);

        VBox view = new VBox(informationLabel, midHBox, bottomHBox);
        view.setSpacing(15);
        return view;

    }

    public void addToChoiceObject(){
        choiceObjectToFind.getItems().add("Intervention");
        choiceObjectToFind.getItems().add("Service");
        choiceObjectToFind.getItems().add("Client");
        choiceObjectToFind.getItems().add("Prestataire");
    }

    public void addToChoiceRow(){

        switch(objectPrint){

            case "Intervention":
                objectDB = "intervention";
                choiceRowToFind.getItems().add("Id");
                choiceRowToFind.getItems().add("Date");
                choiceRowToFind.getItems().add("Agence");
                choiceRowToFind.getItems().add("Id Prestataire");
                choiceRowToFind.getItems().add("Id du service");
                break;

            case "Service":
                objectDB = "service";
                choiceRowToFind.getItems().add("Id");
                choiceRowToFind.getItems().add("Nom du service");
                break;

            case "Client":
                objectDB = "client";
                choiceRowToFind.getItems().add("Id");
                choiceRowToFind.getItems().add("Nom");
                choiceRowToFind.getItems().add("Email");
                choiceRowToFind.getItems().add("Agence");
                break;

            case "Prestataire":
                objectDB = "serviceprovider";
                choiceRowToFind.getItems().add("Id");
                choiceRowToFind.getItems().add("Nom");
                choiceRowToFind.getItems().add("Email");
                choiceRowToFind.getItems().add("Agence");
                break;
        }
    }

    public void removeItem(){
        //remove ALL doesn't work

        switch (objectPrint){
            case "Intervention":
                choiceRowToFind.getItems().remove("Id");
                choiceRowToFind.getItems().remove("Date");
                choiceRowToFind.getItems().remove("Agence");
                choiceRowToFind.getItems().remove("Id Prestataire");
                choiceRowToFind.getItems().remove("Id du service");
                break;

            case "Service":
                choiceRowToFind.getItems().remove("Id");
                choiceRowToFind.getItems().remove("Nom du service");
                break;

            case "Client":
                choiceRowToFind.getItems().remove("Id");
                choiceRowToFind.getItems().remove("Nom");
                choiceRowToFind.getItems().remove("Email");
                choiceRowToFind.getItems().remove("Agence");
                break;

            case "Prestataire":
                choiceRowToFind.getItems().remove("Id");
                choiceRowToFind.getItems().remove("Nom");
                choiceRowToFind.getItems().remove("Email");
                choiceRowToFind.getItems().remove("Agence");
                break;
        }
    }


    private String convertForDBSearch() {

        switch (objectPrint) {

            case "Intervention":
                Object value = choiceRowToFind.getValue();
                if ("Id".equals(value)) {
                    return "interventionID";
                } else if ("Date".equals(value)) {
                    return "dateIntervention";
                } else if ("Agence".equals(value)) {
                    return "agency";
                } else if ("Id Prestataire".equals(value)) {
                    return "providerID";
                } else if ("Id du service".equals(value)) {
                    return "serviceID";
                }

            case "Service":
                Object choiceRowToFindValue = choiceRowToFind.getValue();
                if ("Id".equals(choiceRowToFindValue)) {
                    return "serviceID";
                } else if ("Nom du service".equals(choiceRowToFindValue)) {
                    return "nameService";
                }

            case "Client":
                Object toFindValue = choiceRowToFind.getValue();
                if ("Id".equals(toFindValue)) {
                    return "clientID";
                } else if ("Nom".equals(toFindValue)) {
                    return "lastName";
                } else if ("Email".equals(toFindValue)) {
                    return "email";
                } else if ("Agence".equals(toFindValue)) {
                    return "agency";
                }

            case "Prestataire":
                Object rowToFindValue = choiceRowToFind.getValue();
                if ("Id".equals(rowToFindValue)) {
                    return "providerID";
                } else if ("Nom".equals(rowToFindValue)) {
                    return "lastName";
                } else if ("Email".equals(rowToFindValue)) {
                    return "email";
                } else if ("Agence".equals(rowToFindValue)) {
                    return "agency";
                }
        }return "error";
    }


    public String createQuery(){

        String query = "SELECT * FROM " + objectDB + " WHERE " + convertForDBSearch() + " = " + valueUserWrite.getText();
        return query;
    }

}
