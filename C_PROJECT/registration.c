#include "registration.h"
#include "registration_verif.h"


void sign_in(GtkButton *button1, Inputs *In) {

    const char *lastName = gtk_entry_get_text(GTK_ENTRY(In->lastName));
    const char *firstName = gtk_entry_get_text(GTK_ENTRY(In->firstName));
    const char *email = gtk_entry_get_text(GTK_ENTRY(In->email));
    const char *phoneNumber = gtk_entry_get_text(GTK_ENTRY(In->phoneNumber));
    const char *city = gtk_entry_get_text(GTK_ENTRY(In->city));
    const char *address = gtk_entry_get_text(GTK_ENTRY(In->address));
    const char *professionName = gtk_entry_get_text(GTK_ENTRY(In->professionName));
    const char *contract = gtk_combo_box_text_get_active_text(GTK_COMBO_BOX_TEXT(In->contract));
    int indexContract;

    char *PATH = (char *) malloc(256);
    if(PATH == NULL) {
        printf("Allocation error");
        exit(0);
    }
    sprintf(PATH,"qrcode_%s.bmp",email);

    srand (time(NULL));
    char* password;
    password = malloc(sizeof(char)*7);
    password[0] = lastName[0];
    password[1] = firstName[0];
    sprintf(password+2,"%d",rand()%10);
    sprintf(password+3,"%d",rand()%10);
    sprintf(password+4,"%d",rand()%10);
    sprintf(password+5,"%d",rand()%10);
    password[6] = '\0';

    char *request = (char *) malloc(256);
    if(request == NULL) {
        printf("Allocation error");
        exit(0);
    }

    char *request2 = (char *) malloc(256);
    if(request2 == NULL) {
        printf("Allocation error");
        exit(0);
    }

    int testLastName = nameVerif(lastName);
    int testFirstName = nameVerif(firstName);
    int testEmail = emailVerif(email);
    int testPhoneNumber = phoneNumberVerif(phoneNumber);
    int testCity = nameVerif(city);
    int testAddress = addressVerif(address);
    int testContract = contractVerif(contract);
    int testProfessionName = nameVerif(professionName);

    if(testLastName == 0 && testFirstName == 0 && testEmail == 0 && testAddress == 0 && testPhoneNumber == 0 && testCity == 0 && testProfessionName == 0 && testContract == 0) {

        char *phoneNumberV = malloc(11);
        strcpy(phoneNumberV,"0");
        strcat(phoneNumberV,phoneNumber);

        if(strcmp(contract,"Auto-Entrepreneur") == 0) {
            indexContract = 1;
        } else {
            indexContract = 2;
        }

        sprintf(request, "INSERT INTO useraccount(userID,lastName,firstName,email,password,address,phoneNumber,qrcode,city,userFunction) VALUES ('%d','%s','%s','%s','%s','%s','%s','%s','%s','%d')",In->userID,lastName,firstName,email,password,address,phoneNumberV,PATH,city,1);
        printf("%s\n",request);
        sprintf(request2, "INSERT INTO activity(activityID,professionName,contract,userID) VALUES ('%d','%s','%d','%d')",In->activityID,professionName,indexContract,In->userID);
        printf("\n%s\n",request2);

        //Déclaration du pointeur de structure de type MYSQL
        MYSQL mysql;
        //Initialisation de MySQL
        mysql_init(&mysql);
        //Options de connexion
        mysql_options(&mysql, MYSQL_READ_DEFAULT_GROUP, "option");

        //Si la connexion réussie...
        if (mysql_real_connect(&mysql, "localhost", "root", "", "luxeryservice_parent", 3306, NULL, 0)) {

            if(mysql_query(&mysql, request) != 0) {
                printf("error request\n");
                exit(0);
            }
            if(mysql_query(&mysql, request2) != 0) {
                printf("error request\n");
                exit(0);
            }
            doBasicDemo(email, In->userID, PATH);

        } else {
            printf("Une erreur s'est produite lors de la connexion à la BDD!");
        }
    }
}

int return_last_id(const char *table, const char *tableID) {
    char *request = NULL;
    int id = 0;
    int test_id = 0;
    request = malloc(sizeof(char)*256);
    if(request == NULL) {
        printf("Allocation error");
        exit(0);
    }

    //Déclaration du pointeur de structure de type MYSQL
    MYSQL mysql;
    //Initialisation de MySQL
    mysql_init(&mysql);
    //Options de connexion
    mysql_options(&mysql, MYSQL_READ_DEFAULT_GROUP, "option");

    //Si la connexion réussie...
    if (mysql_real_connect(&mysql, "localhost", "root", "", "luxeryservice_parent", 3306, NULL, 0)) {

        //Requête qui sélectionne userID dans la table useraccount
        sprintf(request, "SELECT %s FROM %s ORDER BY %s",tableID,table,tableID);
        if(mysql_query(&mysql, request) != 0) {
            printf("error request\n");
            return 0;
        }

        //Déclaration des objets
        MYSQL_RES *result = NULL;
        MYSQL_ROW row;
        unsigned int i = 0;

        //On met le jeu de résultat dans le pointeur result
        result = mysql_use_result(&mysql);

        //Tant qu'il y a encore un résultat ...
        while((row = mysql_fetch_row(result))) {

            sscanf(row[i],"%d",&id);
            if(id != test_id) {
               return test_id;
            }

            test_id++;
        }

        //Libération du jeu de résultat
        mysql_free_result(result);
        //Fermeture de MySQL
        mysql_close(&mysql);

    } else {
        printf("Une erreur s'est produite lors de la connexion à la BDD!");
    }

    free(request);
    return test_id;
}
