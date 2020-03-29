package LuxuryService.projet;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;


public class EventFrame implements ActionListener {
    Frame object;
    FileCreation fileCreation = new FileCreation();

    EventFrame(Frame object){
        this.object = object;
    }

    public void actionPerformed(ActionEvent e) {

        if(e.getSource() == object.list){
            object.deleteMainFrame();
            object.addListeFrame();
        }
        else if(e.getSource()== object.other){
            object.deleteMainFrame();
            object.addOtherFrame();
        }
        else if(e.getSource() == object.returnFrame){
            System.out.println("là");
            object.deleteListeFrame();
            object.deleteOtherFrame();
            object.addMainFrame();
        }

        //exports
        else if(e.getSource() == object.executeListe){

            //********Liste Frame***********//

            if (object.choiceListe.getItem(object.choiceListe.getSelectedIndex()) == object.prestataireParisiens){
                System.out.println("sa marche 1");
                //add code pour export
                fileCreation.createFile("ListePrestataireParisien");

            }
            else if (object.choiceListe.getItem(object.choiceListe.getSelectedIndex()) == object.listeService){
                System.out.println("sa marche 2");
                //add code pour export
                fileCreation.createFile("ListeService");

            }
            else if (object.choiceListe.getItem(object.choiceListe.getSelectedIndex()) == object.listeTotPres){
                System.out.println("sa marche 3");
                //add code pour export
                fileCreation.createFile("ListePrestataireTotal");
            }
            else if (object.choiceListe.getItem(object.choiceListe.getSelectedIndex()) == object.listeTotClient){
                System.out.println("sa marche 4");
                //add code pour export
                fileCreation.createFile("ListeClientTotal");
            }
            else if (object.choiceListe.getItem(object.choiceListe.getSelectedIndex()) == object.listeClientParis){
                System.out.println("sa marche 4");
                //add code pour export
                fileCreation.createFile("ListeClientParis");
            }

        }

        else if (e.getSource() == object.executeOther){

            //********Other Frame***********//

            if (object.choiceOther.getItem(object.choiceOther.getSelectedIndex()) == object.bareme){
                System.out.println("sa marche 4");
                //add code pour export
                fileCreation.createFile("Bareme");
            }
        }

    }


}
