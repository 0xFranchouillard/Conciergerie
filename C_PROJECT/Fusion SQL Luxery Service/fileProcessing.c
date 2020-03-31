/*Programme Fusion SQL Luxery Service
Créé par Cédric GARVENES*/

#include "fileProcessing.h"
#include "conflictProcessing.h"

//Lit le dossier contenant les exports
void readFolder(infosGlobal *infoGlobal) {

    DIR* rep = NULL;
    dirent* file = NULL; /* Déclaration d'un pointeur vers la structure dirent. */
    rep = opendir(infoGlobal->exportStorage); /* Ouverture d'un dossier */

    if (rep == NULL) { /* Si le dossier n'a pas pu être ouvert */
        perror(""); /* perror() écrit l'erreur */
        exit(0);
    }
    seekdir(rep, infoGlobal->posDir);
    while ((file = readdir(rep)) != NULL) { /* On lit le répertoire du dossier */
        if (strstr(file->d_name,"export_luxeryservice") != NULL && strstr(file->d_name,".sql") != NULL) {
            infoGlobal->fileName = strdup(file->d_name);
            infoGlobal->posFile = 0;
            infoGlobal->posDir = telldir(rep);
            readFile(infoGlobal);
        }
    }
    free(file);
    closedir(rep);
    exit(0);
}

//Lit tout les INSERT des fichier export
void readFile(infosGlobal *infoGlobal) {

    FILE *file = NULL;
    char *nameFileFull = malloc(sizeof(char)*256);
    char *insertSQL = malloc(sizeof(char)*256);
    if (nameFileFull == NULL && insertSQL == NULL) {
        printf("Allocation error");
        exit(0);
    }

    strcpy(nameFileFull,infoGlobal->exportStorage);
    strcat(nameFileFull,"\\");
    strcat(nameFileFull,infoGlobal->fileName);

    file = fopen(nameFileFull, "r");
    if (file == NULL) {
        perror("");
        exit(0);
    }
    char line[513];
    fseek(file,infoGlobal->posFile+1,SEEK_SET);
    fgetc(file);
    if(feof(file) != 0) {
        readFolder(infoGlobal);
    }
    fseek(file,infoGlobal->posFile,SEEK_SET);
    while (fgets(line, sizeof(line), file) != NULL && infoGlobal->valueConflict != 1) {
        if(strstr(line,"INSERT INTO") != NULL) {
            strncpy(insertSQL, line, strlen(line)-1);
            insertSQL[strlen(line)-1]='\0';

            infoGlobal->valueConflict = insertInto(insertSQL, infoGlobal);
            if (infoGlobal->valueConflict == 1) {

                infosProvider *infoProvider = malloc(sizeof(infosProvider));
                if(infoProvider == NULL) {
                    printf("Allocation error");
                    exit(0);
                }
                infoGlobal->infoProvider = infoProvider;
                getInfos(insertSQL, infoGlobal);
                selectConflict(infoGlobal);

                infoGlobal->posFile = ftell(file);
                gtk_widget_show(infoGlobal->infoGTK->windowConnect);
                gtk_main();
            }
        }
    }
    fclose(file);
}

//Execute l'INSERT récupéré dans le fichier
int insertInto(const char *insert, infosGlobal *infoGlobal) {

    if(mysql_query(&infoGlobal->mysql, insert) != 0) {
        return 1;
    }
    return 0;
}

//Récupère les données de connexion à la BDD contenu dans le fichier 'Configuration.txt'
void recoveryInfoDB(infosDB *infoDB, infosGlobal *infoGlobal){

    char *server = malloc(sizeof(char)*65);
    char *user = malloc(sizeof(char)*65);
    char *password = malloc(sizeof(char)*65);
    char *dataBase = malloc(sizeof(char)*65);
    char *port = malloc(sizeof(char)*5);
    char *exportStorage = malloc(sizeof(char)*129);
    if(server == NULL || user == NULL || password == NULL || dataBase == NULL || port == NULL || exportStorage == NULL) {
        printf("Allocation error");
        exit(0);
    }
    FILE *file = fopen("Configuration.txt", "r");
    if(file == NULL) {
        perror(""); /* perror() écrit l'erreur */
        exit(0);
    }
    char line[129];
    while(fgets(line, sizeof(line), file) != NULL) {
        if(strstr(line, "Server") != NULL) {
            strncpy(server, line+9, strlen(line+9)-1);
            server[strlen(line+9)-1]='\0';
            infoDB->server = strdup(server);
        }
        if(strstr(line, "User") != NULL) {
            strncpy(user, line+7, strlen(line+7)-1);
            user[strlen(line+7)-1]='\0';
            infoDB->user = strdup(user);
        }
        if(strstr(line, "Password") != NULL) {
            strncpy(password, line+11, strlen(line+11)-1);
            password[strlen(line+11)-1]='\0';
            infoDB->password = strdup(password);
        }
        if(strstr(line, "Data Base") != NULL) {
            strncpy(dataBase, line+12, strlen(line+12)-1);
            dataBase[strlen(line+12)-1]='\0';
            infoDB->dataBase = strdup(dataBase);
        }
        if(strstr(line, "Port") != NULL) {
            strncpy(port, line+7, strlen(line+7));
            port[strlen(line+7)]='\0';
            sscanf(port,"%d",&infoDB->port);
        }
        if(strstr(line, "Export storage folder") != NULL) {
            strncpy(exportStorage, line+24, strlen(line+24));
            exportStorage[strlen(line+24)]='\0';
            infoGlobal->exportStorage = strdup(exportStorage);
        }
    }
    fclose(file);
    free(server);
    free(user);
    free(password);
    free(dataBase);
    free(port);
    free(exportStorage);
}
