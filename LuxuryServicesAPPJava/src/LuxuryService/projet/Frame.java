package LuxuryService.projet;

import javax.swing.*;
import java.awt.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.awt.event.WindowListener;

public class Frame extends java.awt.Frame {


    Label title;
    Button list, other, executeListe, executeOther, returnFrame;
    java.awt.Frame mainWindow;
    List choiceListe, choiceOther;

    //String pour choiceListe
    String prestataireParisiens = "Liste des prestataires parisiens";
    String listeService = "Liste des services";
    String listeTotPres = "Liste des prestataires total";
    String listeTotClient = "Liste de tous les clients";
    String listeClientParis = "Liste de tous les clients de Paris";

    //String pour choiceOther
    String bareme = "Barème";

    public Frame(){


        mainWindow = new java.awt.Frame("Luxury Services : outil de requetage");

        mainWindow.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent evt) {
                // Exit the application
                System.exit(0);
            }
        });

        //création des éléments:
        mainFrame();
        secondFrame();

        //register listener
        EventFrame go = new EventFrame(this);

        eventMainFrame(go);
        eventSecondFrame(go);


        //Ajout des éléments
        addMainFrame();


        mainWindow.setSize(720,480);
        mainWindow.setBackground(Color.GRAY);
        mainWindow.setLayout(null);
        mainWindow.setVisible(true);
    }

    ////Creation des elements des differentes frames
    public void mainFrame(){
        title = new Label("Bienvenue sur l'outils de requetage : Veuillez choisir une catégorie de requete.");
        title.setBounds(150,100,430,20);

        list = new Button("Liste");
        list.setBounds(160,200,80,60);

        other = new Button("Autre");
        other.setBounds(490,200,80,60);
    }
    public void secondFrame(){
        returnFrame = new Button("Revenir en arriere");
        returnFrame.setBounds(150,350,430,20);

        choiceListe = new List(4,false);
        choiceListe.setBounds(160,200,200,100);
        choiceListe.add(prestataireParisiens);
        //choiceListe.add(listeService);
        choiceListe.add(listeTotPres);
        choiceListe.add(listeTotClient);
        choiceListe.add(listeClientParis);

        choiceOther = new List(4,false);
        choiceOther.setBounds(160,200,200,100);
        //choiceOther.add(bareme);

        executeListe = new Button("Exporter");
        executeListe.setBounds(490,200,80,60);

        executeOther = new Button("Exporter");
        executeOther.setBounds(490,200,80,60);
    }

    /////Gestion des événements à envoyer dans EventFrame
    public void eventMainFrame(EventFrame go){
        list.addActionListener(go);//envoie dans la classe GoToFrame2
        other.addActionListener(go);
    }

    public void eventSecondFrame(EventFrame go){
        choiceListe.addActionListener(go);
        choiceOther.addActionListener(go);
        executeListe.addActionListener(go);
        executeOther.addActionListener(go);
        returnFrame.addActionListener(go);
    }


    ///Ajout de frame
    public void addMainFrame(){
        title.setText("Bienvenue sur l'outils de requetage : Veuillez choisir une catégorie de requete.");
        mainWindow.add(title);
        mainWindow.add(list);
        mainWindow.add(other);
    }

    public void addListeFrame(){
        title.setText("Choisissez la liste à exporter.");
        mainWindow.add(title);
        mainWindow.add(choiceListe);
        mainWindow.add(executeListe);
        mainWindow.add(returnFrame);
    }

    public void addOtherFrame(){
        title.setText("Choisissez ce que vous voulez exporter.");
        mainWindow.add(title);
        mainWindow.add(choiceOther);
        mainWindow.add(executeOther);
        mainWindow.add(returnFrame);
    }


    //Supression de frame
    public void deleteMainFrame(){
        mainWindow.remove(title);
        mainWindow.remove(list);
        mainWindow.remove(other);
    }

    public void deleteListeFrame(){
        mainWindow.remove(choiceListe);
        mainWindow.remove(returnFrame);
        mainWindow.remove(executeListe);
        mainWindow.remove(title);
    }

    public void deleteOtherFrame(){
        mainWindow.remove(choiceOther);
        mainWindow.remove(returnFrame);
        mainWindow.remove(executeOther);
        mainWindow.remove(title);
    }


}
