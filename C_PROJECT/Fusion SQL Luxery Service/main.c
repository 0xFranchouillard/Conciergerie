#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(int argc,char **argv)
{
    char *verif_user;
    char *verif_password;
    char *verif_db;
    char *user = malloc(sizeof(char)*128+1);
    char *password = malloc(sizeof(char)*128+1);
    char *db = malloc(sizeof(char)*128+1);
    char *export_db = malloc(sizeof(char)*255+1);
    static const char filename[] = "Configuration.txt";
    FILE *file = fopen ( filename, "r" );
    if ( file != NULL ) {
      char line [ 128 ];
      while ( fgets ( line, sizeof line, file ) != NULL ) {
        verif_db = strstr(line, "Data Base");
        verif_user = strstr(line, "User");
        verif_password = strstr(line, "Password");
        if(verif_db != NULL) {
            strncpy(db,line+12,strlen(line+12)-1);
            db[strlen(line+12)-1]='\0';
        }
        if(verif_user != NULL) {
            strncpy(user,line+7,strlen(line+7)-1);
            user[strlen(line+7)-1]='\0';
        }
        if(verif_password != NULL) {
            strncpy(password,line+11,strlen(line+11)-1);
            password[strlen(line+11)-1]='\0';
        }

      }
        sprintf(export_db,"D: & cd D:\\Wamp\\bin\\mysql\\mysql8.0.18\\bin && mysqldump --no-create-info --skip-triggers --skip-add-drop-table --skip-add-locks --skip-disable-keys  --insert-ignore --extended-insert=false -u %s %s > C:\\Users\\User\\Desktop\\export.sql",user,db);
        printf("%s", export_db);
        fclose ( file );
    } else {
        perror ( filename );
    }
    system(export_db);
    system("cd D:\\Wamp\\bin\\mysql\\mysql8.0.18\\bin && mysql -u root luxeryservice_sauvegarde < C:\\Users\\User\\Desktop\\export.sql");

    free(user);
    free(password);
    free(db);
    free(export_db);

    return 0;
}
