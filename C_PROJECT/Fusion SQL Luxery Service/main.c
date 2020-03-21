#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dirent.h>
#include <errno.h>

typedef struct dirent dirent;

void readFile(const char *nameFile);

int main(int argc,char **argv)
{
    DIR* rep = NULL;
    dirent* file = NULL; /* Déclaration d'un pointeur vers la structure dirent. */
    rep = opendir("D:\\ESGI\\ExportPA"); /* Ouverture d'un dossier */

    if (rep == NULL) { /* Si le dossier n'a pas pu être ouvert */
        perror(""); /* perror() écrit l'erreur */
        exit(0);
    }

    while ((file = readdir(rep)) != NULL) { /* On lit le répertoire du dossier */
        if (strstr(file->d_name,"export_luxeryservice") != NULL && strstr(file->d_name,".sql") != NULL) {
            readFile(file->d_name);
        }
    }

    if (closedir(rep) == -1) { /* S'il y a eu un souci avec la fermeture */
        perror(""); /* perror() écrit l'erreur */
        exit(0);
    }

    return 0;
}

void readFile(const char *nameFile) {

    FILE *file = NULL;
    char *nameFileFull = malloc(sizeof(char)*256);
    char *insertSQL = malloc(sizeof(char)*256);
    if (nameFileFull == NULL && insertSQL == NULL) {
        printf("Allocation error");
        exit(0);
    }

    strcpy(nameFileFull,"D:\\ESGI\\ExportPA\\");
    strcat(nameFileFull,nameFile);

    file = fopen(nameFileFull, "r");
    if (file == NULL) {
        perror("");
        exit(0);
    }
    char line[256];
    while (fgets(line, sizeof(line), file) != NULL) {
        if(strstr(line,"INSERT INTO") != NULL) {
            strncpy(insertSQL, line, strlen(line)-1);
            insertSQL[strlen(line)-1]='\0';
            printf("%s\n",insertSQL);
        }
    }

}
