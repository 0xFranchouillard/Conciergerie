#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(int argc,char **argv)
{
    char *server = malloc(sizeof(char)*65);
    char *user = malloc(sizeof(char)*65);
    char *password = malloc(sizeof(char)*65);
    char *dataBase = malloc(sizeof(char)*65);
    int port;
    char *mysqlLocalization = malloc(sizeof(char)*129);
    char *exportStorage = malloc(sizeof(char)*129);
    char *exportDB = malloc(sizeof(char)*326);
    FILE *file = fopen("Configuration.txt", "r");
    if(file == NULL) {
        printf("Impossible d'ouvrir \"Configuration.txt\"");
        exit(0);
    }
    char line[64];
    while(fgets(line, sizeof(line), file) != NULL) {
        if(strstr(line, "Server") != NULL) {
            strncpy(server, line+9, strlen(line+9)-1);
            server[strlen(line+9)-1]='\0';
        }
        if(strstr(line, "User") != NULL) {
            strncpy(user, line+7, strlen(line+7)-1);
            user[strlen(line+7)-1]='\0';
        }
        if(strstr(line, "Password") != NULL) {
            if(strlen(line+11)-1 != 0){
                strcpy(password,"-p");
                strncat(password, line+11, strlen(line+11)-1);
                password[strlen(line+11)+3]='\0';
            } else {
                strncpy(password, line+11, strlen(line+11)-1);
                password[strlen(line+11)-1]='\0';
            }
        }
        if(strstr(line, "Data Base") != NULL) {
            strncpy(dataBase, line+12, strlen(line+12)-1);
            dataBase[strlen(line+12)-1]='\0';
        }
        if(strstr(line, "Port") != NULL) {
            sscanf(line+7,"%d",&port);
        }
        if(strstr(line, "MySQL Localization") != NULL) {
            strncpy(mysqlLocalization, line+21, strlen(line+21)-1);
            mysqlLocalization[strlen(line+21)-1]='\0';
        }
        if(strstr(line, "Export storage folder") != NULL) {
            strncpy(exportStorage, line+24, strlen(line+24));
            exportStorage[strlen(line+24)]='\0';
        }
    }

    sprintf(exportDB,"D: & cd %s && mysqldump --skip-triggers --compact --no-create-info --extended-insert=false -P %d -h %s -u %s %s %s serviceprovider > %s\\export_%s.sql", mysqlLocalization, port, server, user, password, dataBase, exportStorage, dataBase);
    printf("%s\n",exportDB);

    system(exportDB);

    free(server);
    free(user);
    free(password);
    free(dataBase);
    free(mysqlLocalization);
    free(exportStorage);
    free(exportDB);

    return 0;
}
