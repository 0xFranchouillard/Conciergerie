/*Programme Fusion SQL Luxery Service
Créé par Cédric GARVENES, Cyrille CHAMPION et Arthur BRONGNIART*/

#include "conflictProcessing.h"

void skipConflict(GtkWidget *skipButton, infosGlobal *infoGlobal) {
    infoGlobal->valueConflict = 0;
    readFile(infoGlobal);
}

void updateConflict(GtkWidget *updateButton, infosGlobal *infoGlobal) {
    char *request = malloc(sizeof(char)*513);

    sprintf(request, "UPDATE serviceprovider SET lastName = '%s', firstName = '%s', email = '%s', city = '%s', address = '%s', phoneNumber = '%s' WHERE providerID = %s && agency = '%s'",infoGlobal->infoProvider->lastName,infoGlobal->infoProvider->firstName,infoGlobal->infoProvider->email,infoGlobal->infoProvider->city,infoGlobal->infoProvider->address,infoGlobal->infoProvider->phoneNumber,infoGlobal->infoProvider->providerID,infoGlobal->infoProvider->agency);

    if(mysql_query(&infoGlobal->mysql, request) != 0) {
        printf("Request error\n");
        exit(0);
    }

    free(request);

    infoGlobal->valueConflict = 0;
    readFile(infoGlobal);
}

void getInfos(const char *insert, infosGlobal *infoGlobal) {
    char *providerID = malloc(sizeof(char)*12);
    char *agency = malloc(sizeof(char)*51);
    char *lastName = malloc(sizeof(char)*51);
    char *firstName = malloc(sizeof(char)*51);
    char *email = malloc(sizeof(char)*141);
    char *city = malloc(sizeof(char)*51);
    char *address = malloc(sizeof(char)*141);
    char *phoneNumber = malloc(sizeof(char)*11);
    if(providerID == NULL || agency == NULL || lastName == NULL || firstName == NULL || email == NULL || city == NULL || address == NULL || phoneNumber == NULL) {
        printf("Allocation error\n");
        exit(0);
    }

    providerID = strdup(strstr(insert,"(")+1);
    agency = strdup(strstr(providerID,"'")+1);
    lastName = strdup(strstr(agency,",'")+2);
    firstName = strdup(strstr(lastName,",'")+2);
    email = strdup(strstr(firstName,",'")+2);
    city = strdup(strstr(email,",'")+2);
    city = strdup(strstr(city,",'")+2); // on remplace le password par city
    address = strdup(strstr(city,",'")+2);


    phoneNumber = strdup(strstr(address,"'")+2);
    phoneNumber = strdup(strtok(phoneNumber,","));
    if(strstr(phoneNumber,"NULL") == NULL) {
        phoneNumber = strdup(strstr(address,",'")+2);
        phoneNumber = strdup(strtok(phoneNumber,"'"));
    }

    providerID = strdup(strtok(providerID,","));
    agency = strdup(strtok(agency,"'"));
    lastName = strdup(strtok(lastName,"'"));
    firstName = strdup(strtok(firstName,"'"));
    email = strdup(strtok(email,"'"));
    city = strdup(strtok(city,"'"));
    address = strdup(strtok(address,"'"));

    infoGlobal->infoProvider->providerID = strdup(providerID);
    infoGlobal->infoProvider->agency = strdup(agency);
    infoGlobal->infoProvider->lastName = strdup(lastName);
    infoGlobal->infoProvider->firstName = strdup(firstName);
    infoGlobal->infoProvider->email = strdup(email);
    infoGlobal->infoProvider->city = strdup(city);
    infoGlobal->infoProvider->address = strdup(address);
    infoGlobal->infoProvider->phoneNumber = strdup(phoneNumber);

    free(providerID);
    free(agency);
    free(lastName);
    free(firstName);
    free(email);
    free(city);
    free(address);
    free(phoneNumber);

}

void selectConflict(infosGlobal *infoGlobal) {

    char *request = malloc(sizeof(char)*513);
    char *valueLabel = malloc(sizeof(char)*513);
    MYSQL_RES *result = NULL;
    MYSQL_ROW row;
    unsigned int i = 0;
    char **valueProvider;
    valueProvider = malloc(sizeof(char*)*8);
    if(valueProvider == NULL) {
        printf("Allocation error\n");
        exit(0);
    }

    sprintf(request,"SELECT providerID, agency, lastName, firstName, email, city, address, phoneNumber FROM serviceprovider WHERE providerID = %s && agency = '%s'", infoGlobal->infoProvider->providerID, infoGlobal->infoProvider->agency);

    if(mysql_query(&infoGlobal->mysql, request) != 0) {
        printf("error request\n");
        exit(0);
    }

    //On met le jeu de résultat dans le pointeur result
    result = mysql_use_result(&infoGlobal->mysql);

    row = mysql_fetch_row(result);
    //On fait une boucle pour avoir la valeur de chaque champs
    for(i = 0; i < 8; i++) {
        valueProvider[i] = malloc(sizeof(char)*141);
        valueProvider[i] = row[i] ? strdup(row[i]) : strdup("NULL");
    }

    //Libération du jeu de résultat
    mysql_free_result(result);

    sprintf(valueLabel,"BDD : %s, %s, %s, %s, %s, %s, %s, %s \n\nConflict with : %s, %s, %s, %s, %s, %s, %s, %s",valueProvider[0],valueProvider[1],valueProvider[2],valueProvider[3],valueProvider[4],valueProvider[5],valueProvider[6],valueProvider[7],
infoGlobal->infoProvider->providerID,infoGlobal->infoProvider->agency,infoGlobal->infoProvider->lastName,infoGlobal->infoProvider->firstName,infoGlobal->infoProvider->email,infoGlobal->infoProvider->city,infoGlobal->infoProvider->address,infoGlobal->infoProvider->phoneNumber);

    gtk_label_set_label(GTK_LABEL(infoGlobal->infoGTK->value), valueLabel);

    free(request);
    free(valueLabel);
    for(i = 0; i < 8; i++) {
        free(valueProvider[i]);
    }
    free(valueProvider);

}
