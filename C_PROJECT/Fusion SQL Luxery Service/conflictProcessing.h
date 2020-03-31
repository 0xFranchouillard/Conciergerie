/*Programme Fusion SQL Luxery Service
Créé par Cédric GARVENES, Cyrille CHAMPION et Arthur BRONGNIART*/

#ifndef CONFLICTPROCESSING_H_INCLUDED
#define CONFLICTPROCESSING_H_INCLUDED

#include "fileProcessing.h"

void skipConflict(GtkWidget *insertButton, infosGlobal *infoGlobal);
void updateConflict(GtkWidget *updateButton, infosGlobal *infoGlobal);
void getInfos(const char *insert, infosGlobal *infoGlobal);
void selectConflict(infosGlobal *infoGlobal);

#endif // CONFLICTPROCESSING_H_INCLUDED
