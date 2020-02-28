#ifndef REGISTRATION_H_INCLUDED
#define REGISTRATION_H_INCLUDED

#include <stdio.h>
#include <stdlib.h>
#include <winsock.h>
#include <MYSQL/mysql.h>
#include <string.h>
#include <stdbool.h>
#include <stddef.h>
#include <stdint.h>
#include <malloc.h>
#include <time.h>
#include "qrcodegen.h"
#include <gtk/gtk.h>
#include "qr.h"
#include "registration_verif.h"


typedef struct {
    gpointer lastName;
    gpointer firstName;
    gpointer email;
    gpointer phoneNumber;
    gpointer city;
    gpointer address;
    gpointer professionName;
    gpointer contract;
    int userID;
    int activityID;
}Inputs;

void sign_in(GtkWidget *entry, Inputs *In);
int return_last_id(const char *table, const char *tableID);

#endif // REGISTRATION_H_INCLUDED
