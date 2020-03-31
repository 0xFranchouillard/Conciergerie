/*Programme Fusion SQL Luxery Service
Créé par Cédric GARVENES, Cyrille CHAMPION et Arthur BRONGNIART*/

#include "fileProcessing.h"
#include "conflictProcessing.h"

void Launcher(infosDB *infoDB, infosGlobal *infoGlobal);
void mainGTK(infosGlobal *infoGlobal);
void on_window_connect_destroy();

int main(int argc,char **argv)
{
    infosGTK *infoGTK = malloc(sizeof(infosGTK));
    infosDB *infoDB = malloc(sizeof(infosDB));
    infosGlobal *infoGlobal = malloc(sizeof(infosGlobal));
    if(infoDB == NULL || infoGTK == NULL || infoGlobal == NULL) {
        printf("Allocation error");
        exit(0);
    }
    infoGlobal->infoGTK = infoGTK;
    recoveryInfoDB(infoDB, infoGlobal);

    gtk_init(&argc, &argv);

    //Déclaration du pointeur de structure de type MYSQL
    MYSQL mysql;
    infoGlobal->mysql = mysql;
    Launcher(infoDB, infoGlobal);

    free(infoDB);
    free(infoGTK);
    free(infoGlobal);

    return 0;
}

void Launcher(infosDB *infoDB, infosGlobal *infoGlobal) {
    //Initialisation de MYSQL
    mysql_init(&infoGlobal->mysql);
    //Options de connexion
    mysql_options(&infoGlobal->mysql, MYSQL_READ_DEFAULT_GROUP, "option");

    //Si la connection réussie...
    if (mysql_real_connect(&infoGlobal->mysql, infoDB->server, infoDB->user, infoDB->password, infoDB->dataBase, infoDB->port, NULL, 0)) {
        mainGTK(infoGlobal);
        readFolder(infoGlobal);printf("hello\n");
        mysql_close(&infoGlobal->mysql);
        gtk_widget_show(infoGlobal->infoGTK->windowConnect);
        gtk_main();
    } else {
        printf("Une erreur s'est produite lors de la connexion à la BDD!\n");
    }
}

void mainGTK(infosGlobal *infoGlobal) {
    GtkWidget *skipButton;
    GtkWidget *updateButton;
    GtkWidget *background;
    GtkWidget *logo;

    infoGlobal->infoGTK->gtkBuilder = gtk_builder_new();
    gtk_builder_add_from_file(infoGlobal->infoGTK->gtkBuilder, "graphique.glade", NULL);

    infoGlobal->infoGTK->windowConnect = GTK_WIDGET(gtk_builder_get_object(infoGlobal->infoGTK->gtkBuilder, "window_connect"));
    infoGlobal->infoGTK->value = GTK_WIDGET(gtk_builder_get_object(infoGlobal->infoGTK->gtkBuilder, "Value"));
    skipButton = GTK_WIDGET(gtk_builder_get_object(infoGlobal->infoGTK->gtkBuilder, "skip"));
    updateButton = GTK_WIDGET(gtk_builder_get_object(infoGlobal->infoGTK->gtkBuilder, "update"));
    background = GTK_WIDGET(gtk_builder_get_object(infoGlobal->infoGTK->gtkBuilder, "Background"));
    logo = GTK_WIDGET(gtk_builder_get_object(infoGlobal->infoGTK->gtkBuilder, "Logo"));
    gtk_image_set_from_file(GTK_IMAGE(background), "images/backgroundApp2.png");
    gtk_image_set_from_file(GTK_IMAGE(logo), "images/logoApp.png");

    g_signal_connect(skipButton, "clicked", G_CALLBACK(skipConflict), infoGlobal);
    g_signal_connect(updateButton, "clicked", G_CALLBACK(updateConflict), infoGlobal);
    g_signal_connect(infoGlobal->infoGTK->windowConnect, "destroy", G_CALLBACK(on_window_connect_destroy), NULL);
}

void on_window_connect_destroy() {
    gtk_main_quit();
}


