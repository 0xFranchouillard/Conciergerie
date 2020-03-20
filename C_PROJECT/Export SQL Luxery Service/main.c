#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(int argc,char **argv)
{
    char *verifServer;
    char *verifUser;
    char *verifPassword;
    char *verifDataBase;
    char *verifPort;
    char *server = malloc(sizeof(char)*65);
    char *user = malloc(sizeof(char)*65);
    char *password = malloc(sizeof(char)*65);
    char *dataBase = malloc(sizeof(char)*65);
    //char *port = malloc(sizeof(char)*5);
    int port;
    char *exportDB = malloc(sizeof(char)*326);
    FILE *file = fopen("Configuration.txt", "r");
    if(file == NULL) {
        printf("Impossible d'ouvrir \"Configuration.txt\"");
        exit(0);
    }
    char line[64];
    while(fgets(line, sizeof(line), file) != NULL) {
        verifServer = strstr(line, "Server");
        verifUser = strstr(line, "User");
        verifPassword = strstr(line, "Password");
        verifDataBase = strstr(line, "Data Base");
        verifPort = strstr(line, "Port");
        if(verifServer != NULL) {
            strncpy(server, line+9, strlen(line+9)-1);
            server[strlen(line+9)-1]='\0';
        }
        if(verifUser != NULL) {
            strncpy(user, line+7, strlen(line+7)-1);
            user[strlen(line+7)-1]='\0';
        }
        if(verifPassword != NULL) {
            if(strlen(line+11)-1 != 0){
                strcpy(password,"-p");
                strncat(password, line+11, strlen(line+11)-1);
                password[strlen(line+11)+3]='\0';
            } else {
                strncpy(password, line+11, strlen(line+11)-1);
                password[strlen(line+11)-1]='\0';
            }
        }
        if(verifDataBase != NULL) {
            strncpy(dataBase, line+12, strlen(line+12)-1);
            dataBase[strlen(line+12)-1]='\0';
        }
        if(verifPort != NULL) {
            sscanf(line+7,"%d",&port);
            //strncpy(port, line+7, strlen(line+7));
            //port[strlen(line+7)]='\0';
        }
    }

    sprintf(exportDB,"D: & cd D:\\Wamp\\bin\\mysql\\mysql8.0.18\\bin && mysqldump --skip-triggers --compact --no-create-info --extended-insert=false -P %d -h %s -u %s %s %s serviceprovider > C:\\Users\\User\\Desktop\\export.sql", port, server, user, password, dataBase);
    //printf("%s\n",exportDB);

    system(exportDB);

    return 0;
}
