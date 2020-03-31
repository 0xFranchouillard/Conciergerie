/*Programme Fusion SQL Luxery Service
Créé par Cédric GARVENES, Cyrille CHAMPION et Arthur BRONGNIART*/

#ifndef FILEPROCESSING_H_INCLUDED
#define FILEPROCESSING_H_INCLUDED

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dirent.h>
#include <errno.h>
#include <winsock.h>
#include <MYSQL/mysql.h>
#include <gtk/gtk.h>


typedef struct {
    const char *server;
    const char *dataBase;
    const char *user;
    const char *password;
    int port;
}infosDB;

typedef struct {
    GtkBuilder *gtkBuilder;
    GtkWidget *windowConnect;
    GtkWidget *value;
}infosGTK;

typedef struct {
    const char *providerID;
    const char *agency;
    const char *lastName;
    const char *firstName;
    const char *email;
    const char *city;
    const char *address;
    const char *phoneNumber;
}infosProvider;

typedef struct {
    infosGTK *infoGTK;
    MYSQL mysql;
    const char *exportStorage;
    const char *fileName;
    long posDir;
    long posFile;
    int valueConflict;
    infosProvider *infoProvider;
}infosGlobal;

typedef struct dirent dirent;

void readFolder(infosGlobal *infoGlobal);
void readFile(infosGlobal *infoGlobal);
int insertInto(const char *insert, infosGlobal *infoGlobal);
void recoveryInfoDB(infosDB *infoDB, infosGlobal *infoGlobal);

#endif // FILEPROCESSING_H_INCLUDED
