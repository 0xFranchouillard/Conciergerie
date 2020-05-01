package sample;

import javafx.scene.control.Button;
import javafx.scene.control.Label;;
import javafx.scene.control.ScrollPane;
import javafx.scene.layout.VBox;

public class PrintQueryScene {

    public VBox showQuery(Button execute, Button returnMainScene, String result ){

        ScrollPane scrollPane = new ScrollPane();
        Label label = new Label(result);


        scrollPane.setContent(label);
        scrollPane.pannableProperty().set(true);
        

        VBox box = new VBox(scrollPane,execute, returnMainScene);

        box.setSpacing(20);

        return box;
    }

}
