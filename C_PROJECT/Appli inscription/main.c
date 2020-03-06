#include <stdio.h>
#include <stdlib.h>
#include <winsock.h>
#include <MYSQL/mysql.h>
#include <stdbool.h>
#include <stddef.h>
#include <stdint.h>
#include <malloc.h>
#include <time.h>
#include "qrcodegen.h"
#include "qrcodegen.c"
#include <gtk/gtk.h>
#include "qr.h"
#include "registration.h"
#include "registration_verif.h"


void on_window_connect_destroy();

int main(int argc, char *argv[]) {

    GtkBuilder *gtkBuilder;
    GtkWidget *window_connect;
    GtkWidget *button1;

    gtk_init(&argc, &argv);
    gtkBuilder = gtk_builder_new();
    gtk_builder_add_from_file(gtkBuilder, "gui.glade", NULL);

    window_connect = GTK_WIDGET(gtk_builder_get_object(gtkBuilder, "window_connect"));
    button1 = GTK_WIDGET(gtk_builder_get_object(gtkBuilder, "button1"));

    Inputs *Input;
    Input = malloc(sizeof(Inputs));
    if(Input == NULL) {
        exit(0);
    }
    Input->lastName = gtk_builder_get_object(gtkBuilder, "lastName");
    Input->firstName = gtk_builder_get_object(gtkBuilder, "firstName");
    Input->email = gtk_builder_get_object(gtkBuilder, "email");
    Input->phoneNumber = gtk_builder_get_object(gtkBuilder, "phoneNumber");
    Input->city = gtk_builder_get_object(gtkBuilder, "city");
    Input->address = gtk_builder_get_object(gtkBuilder, "address");
    Input->professionName = gtk_builder_get_object(gtkBuilder, "professionName");
    Input->contract = gtk_builder_get_object(gtkBuilder, "contract");
    Input->userID = return_last_id("UserAccount","userID");
    Input->activityID = return_last_id("Activity","activityID");

    //gtk_builder_connect_signals(gtkBuilder, NULL);
    g_signal_connect(window_connect, "destroy", G_CALLBACK(on_window_connect_destroy), NULL);
    g_signal_connect(button1, "clicked", G_CALLBACK(sign_in), Input);
    //g_object_unref(gtkBuilder);
    gtk_widget_show(window_connect);
    gtk_main();
    free(Input);

    return 0;
}

void on_window_connect_destroy() {
    gtk_main_quit();
}
