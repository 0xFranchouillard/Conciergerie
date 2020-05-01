package sample;

import javafx.application.Application;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.scene.text.Font;
import javafx.stage.Stage;
import javafx.stage.StageStyle;



public class Main extends Application {

    ExportationSubscription exportationSubscription = new ExportationSubscription();
    ExportationSubscribes exportationSubscribes = new ExportationSubscribes();
    ExportationClient exportationClient = new ExportationClient();
    ExportationBill exportationBill = new ExportationBill();
    ExportationIntervention exportationIntervention = new ExportationIntervention();
    ExportationServiceProvider exportationServiceProvider = new ExportationServiceProvider();
    ExportationService exportationService = new ExportationService();
    ExportationTariff exportationTariff = new ExportationTariff();

    FindInformation findInformation = new FindInformation();
    DatabaseAndFile databaseAndFile = new DatabaseAndFile();
    PrintQueryScene printQueryScene = new PrintQueryScene();

    String choiceName = "";
    Button execute = new Button("Exporter");
    Button returnMainScene = new Button("Retour");
    Button printResult = new Button("Afficher");
    private int isCherching;


    @Override
    public void start(Stage primaryStage){

        primaryStage.setTitle("Outil de requetage Luxury Service");

        Label informationLabel = new Label("Outils de requetage Luxury Services");
        ChoiceBox choiceTable = new ChoiceBox();
        ChoiceBox choiceExport = new ChoiceBox();
        Button chooseButton = new Button("Valider");
        String compare = "SELEC FROM";

        isCherching = 0;

        informationLabel.setFont(new Font(16));

        addItemChoiceTable(choiceTable);
        addItemExport(choiceExport);
        findInformation.addToChoiceObject();

        Scene scene  = new Scene(mainScene(informationLabel,choiceTable,chooseButton));
        Stage stage = new Stage();

        stage.setWidth(1200);
        stage.setHeight(800);
        stage.setScene(scene);
        stage.initStyle(StageStyle.DECORATED);

        stage.show();


        chooseButton.setOnAction(actionEvent ->  {
            try {
                String value = (String) choiceTable.getValue();
                Scene exportScene = new Scene(exportationScene(informationLabel,value,choiceExport,execute,returnMainScene,printResult));
                stage.setScene(exportScene);
            }
            catch (NullPointerException e){
                informationLabel.setText("Veuillez choisir une catégorie");
            }
        });

        execute.setOnAction(actionEvent ->  {

            String query = switchGetQuery(choiceName);

            if (compare.equals(query.substring(0,10)) ||  (isCherching == 1 && findInformation.valueUserWrite.getText().isEmpty() )|| (isCherching == 1 && findInformation.choiceRowToFind.getValue() == null )){
                //informationLabel.setText("Aucun champs choisis, Retour au menu");
                choiceName = ""; //the variable is reset for reuse
                databaseAndFile.result = "";
                databaseAndFile.fileName = "";
                findInformation.removeItem();
                stage.setScene(scene); // reset the choice
            }else{

                String exportFile = (String) choiceExport.getValue();

                if( exportFile == null){
                    informationLabel.setText("Veuillez choisir un type d'export !");
                }
                else{
                    databaseAndFile.connectSQl(query, choiceName,exportFile,false);
                    //informationLabel.setText("Document crée avec succès !");
                    choiceName = ""; //the variable is reset for reuse
                    databaseAndFile.result = "";
                    databaseAndFile.fileName = "";
                    isCherching = 0;
                    stage.setScene(scene); // reset the choice
                }

            }
        });

        printResult.setOnAction(actionEvent ->  {

            String query = switchGetQuery(choiceName);

            if (compare.equals(query.substring(0,10)) ||  (isCherching == 1 && findInformation.valueUserWrite.getText().isEmpty() )|| (isCherching == 1 && findInformation.choiceRowToFind.getValue() == null )) {
                stage.setScene(scene); // reset the choice
                choiceName = ""; //the variable is reset for reuse
                databaseAndFile.result = "";
                databaseAndFile.fileName = "";
                findInformation.removeItem();
                isCherching = 0;
            }else{
                String exportFile = (String) choiceExport.getValue();//useless here but need to deal with it
                databaseAndFile.connectSQl(query, choiceName,exportFile,true);
                Scene printScene = new Scene(printQueryScene.showQuery(execute,returnMainScene,databaseAndFile.getResult()));
                stage.setScene(printScene);
            }
        });

        returnMainScene.setOnAction(actionEvent -> {
            informationLabel.setText("Outils de requetage Luxury Services");
            stage.setScene(scene);
            choiceName = ""; //the variable is reset for reuse
            databaseAndFile.result = "";
            databaseAndFile.fileName = "";
            findInformation.removeItem();
            isCherching = 0;
        });

        findInformation.validObject.setOnAction(actionEvent ->  {
            try {
                findInformation.removeItem();
                findInformation.objectPrint = (String) findInformation.choiceObjectToFind.getValue();
                findInformation.addToChoiceRow();
                isCherching = 1;
            }
            catch (NullPointerException e){
                informationLabel.setText("Veuillez choisir un objet");
            }
        });

    }


    public static void main(String[] args) {
        launch(args);
    }

    private void addItemChoiceTable(ChoiceBox choiceTable){

        choiceTable.getItems().add("Abonnement");
        choiceTable.getItems().add("Abonné");
        choiceTable.getItems().add("Client");
        choiceTable.getItems().add("Facture");
        choiceTable.getItems().add("Intervention");
        choiceTable.getItems().add("Prestataire");
        choiceTable.getItems().add("Service");
        choiceTable.getItems().add("Tarif");
        choiceTable.getItems().add("Recherche");

    }

    private void addItemExport(ChoiceBox choiceBox){
        choiceBox.getItems().add("TXT");
        choiceBox.getItems().add("PDF");
    }

    private VBox mainScene(Label informationLabel, ChoiceBox choiceTable, Button chooseButton){

        chooseButton.setWrapText(true);

        HBox hBox = new HBox(choiceTable,chooseButton);
        VBox vbox = new VBox(informationLabel,hBox);
        hBox.setSpacing(20);
        vbox.setSpacing(20);
        return vbox;
    }

    private VBox exportationScene(Label informationLabel, String value, ChoiceBox choiceExport, Button execute, Button returnMainScene, Button printResult){
        VBox view;

        switch (value){
            case "Abonné":
                choiceName = "Abonné";
                view = exportationSubscribes.exportationSubscribes(informationLabel,execute,choiceExport,returnMainScene, printResult);
                break;

            case "Abonnement":
                choiceName = "Abonnement";
                view = exportationSubscription.exportationSubscription(informationLabel,execute,choiceExport,returnMainScene, printResult);
                break;

            case "Client":
                choiceName ="Client";
                view = exportationClient.exportationClient(informationLabel,execute,choiceExport, returnMainScene, printResult);
                break;

            case "Facture":
                choiceName ="Facture";
                view = exportationBill.exportationBill(informationLabel,execute,choiceExport,returnMainScene, printResult);
            break;

            case "Intervention":
                choiceName ="Intervention";
                view = exportationIntervention.exportationIntervention(informationLabel,execute,choiceExport, returnMainScene, printResult);
            break;

            case "Tarif":
                choiceName ="Tarif";
                view = exportationTariff.exportationTariff(informationLabel,execute,choiceExport,returnMainScene, printResult);
            break;

            case "Prestataire":
                choiceName ="Prestataire";
                view = exportationServiceProvider.exportationServiceProvider(informationLabel,execute,choiceExport, returnMainScene, printResult);
            break;

            case "Service":
                choiceName ="Service";
                view = exportationService.exportationService(informationLabel,execute,choiceExport, returnMainScene, printResult);
                break;

            case "Recherche":
                choiceName ="Recherche";
                view = findInformation.findInfo(informationLabel,execute,choiceExport, returnMainScene, printResult);
                break;

            default:
                throw new IllegalStateException("Unexpected value: " + value);
        }

        return view;
    }

    private String switchGetQuery(String query){
        switch (query){

            case "Abonné":
                query = exportationSubscribes.sqlLineSubscribes();
                break;

            case "Abonnement":
                query = exportationSubscription.sqlLineSubscription();
                break;

            case "Client":
                query = exportationClient.sqlLineClient();
                break;

            case "Facture":
                query = exportationBill.sqlLineBill();
            break;

            case "Intervention":
                query = exportationIntervention.sqlLineIntervention();
            break;

            case "Tarif":
                query = exportationTariff.sqlLineTraiff();
            break;

            case "Prestataire":
                query = exportationServiceProvider.sqlLineServiceProvider();
            break;

            case "Service":
                query = exportationService.sqlLineService();
                break;

            case "Recherche":
                query = findInformation.createQuery();
                break;

            default:
                throw new IllegalStateException("Unexpected value: " + query);
        }

        return query ;

    }
}
