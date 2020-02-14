#include <stdio.h>
#include <stdlib.h>
#include <winsock.h>
#include <MYSQL/mysql.h>
#include <stdbool.h>
#include <stddef.h>
#include <stdint.h>
#include <malloc.h>
#include <time.h>
#include "qrcodegen.h"
#include "qrcodegen.c"
#include <gtk/gtk.h>

#pragma pack(push, 1)

#define _height (size+border*2)*16
#define _width (size+border*2)*16
#define _bitsperpixel 24
#define _planes 1
#define _compression 0
#define _pixelbytesize _height*_width*_bitsperpixel/8
#define _filesize _pixelbytesize+sizeof(bitmap)
#define _xpixelpermeter 0x130B //2835 , 72 DPI
#define _ypixelpermeter 0x130B //2835 , 72 DPI

typedef struct {
    /*uint8_t*/char signature[2];
    uint32_t filesize;
    uint32_t reserved;
    uint32_t fileoffset_to_pixelarray;
} fileheader;

typedef struct {
    uint32_t dibheadersize;
    uint32_t width;
    uint32_t height;
    uint16_t planes;
    uint16_t bitsperpixel;
    uint32_t compression;
    uint32_t imagesize;
    uint32_t ypixelpermeter;
    uint32_t xpixelpermeter;
    uint32_t numcolorspallette;
    uint32_t mostimpcolor;
} bitmapinfoheader;

typedef struct {
    fileheader fileheader;
    bitmapinfoheader bitmapinfoheader;
} bitmap;

typedef struct Inputs Inputs;
struct Inputs {
    gpointer entry1;
    gpointer entry2;
    gpointer entry3;
    gpointer entry4;
    gpointer entry5;
    gpointer entry6;
    gpointer entry7;
    gpointer city;
    gpointer checkbutton1;
};

#pragma pack(pop)

void on_window_connect_destroy();

static void doBasicDemo(char *mail, int id, char* PATH);
static void printQr(const uint8_t qrcode[], char* PATH);
int return_last_id();



void sign_in(GtkWidget *entry, Inputs *In) {
    char *lastName = gtk_entry_get_text(GTK_ENTRY(In->entry1));
    char *firstName = gtk_entry_get_text(GTK_ENTRY((*In).entry2));
    char *email = gtk_entry_get_text(GTK_ENTRY((*In).entry3));
    //char *professionName = gtk_entry_get_text(GTK_ENTRY((*In).entry4));
    char *address = gtk_entry_get_text(GTK_ENTRY((*In).entry5));
    char *phoneNumber = gtk_entry_get_text(GTK_ENTRY((*In).entry6));
    char *city = gtk_combo_box_text_get_active_text(GTK_COMBO_BOX_TEXT(In->city));
    char *PATH = (char *) malloc(256);
    sprintf(PATH,"qrcode_%s.bmp",email);
    char* idUser = "NULL";

    char *request = (char *) malloc(256);
    srand (time(NULL));
    char* password;
    password = malloc(sizeof(char)*5);
    sprintf(password,"%d",rand()%100000);

    sprintf(request, "INSERT INTO useraccount(userID,lastName,firstName,email,password,address,phoneNumber,qrcode,city,userFunction) VALUES ('%s','%s','%s','%s','%s','%s','%d','%s','%s','%d')",idUser,lastName,firstName,email,password,address,phoneNumber,PATH,city,1);
    printf("%s\n",request);

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql, MYSQL_READ_DEFAULT_GROUP, "option");
    if (mysql_real_connect(&mysql, "localhost", "root", "", "mydb", 3306, NULL, 0)) {
        mysql_query(&mysql, request);
        doBasicDemo(email, idUser, PATH);
    } else { printf("non ok "); }
}

static void doBasicDemo(char *email, int idUser,char* PATH) {
    char *value = (char *) malloc(256);
    sprintf(value, "https://51.77.221.39/verif.php?email='%s'", email);
    enum qrcodegen_Ecc errCorLvl = qrcodegen_Ecc_LOW;  // Error correction level

    // Make and print the QR Code symbol
    uint8_t qrcode[qrcodegen_BUFFER_LEN_MAX];
    uint8_t tempBuffer[qrcodegen_BUFFER_LEN_MAX];
    bool ok = qrcodegen_encodeText(value, tempBuffer, qrcode, errCorLvl,
                                   qrcodegen_VERSION_MIN, qrcodegen_VERSION_MAX, qrcodegen_Mask_AUTO, true);
    if (ok)
        printQr(qrcode,PATH);
}

static void printQr(const uint8_t qrcode[],char *PATH) {

    int size = qrcodegen_getSize(qrcode);
    int border = 2;
    FILE *fp = fopen(PATH, "wb");
    bitmap *pbitmap = (bitmap *) calloc(1, sizeof(bitmap));
    /*uint8_t*/char *pixelbuffer = (/*uint8_t*/char *) malloc(_pixelbytesize);
    strcpy(pbitmap->fileheader.signature, "BM");
    pbitmap->fileheader.filesize = _filesize;
    pbitmap->fileheader.fileoffset_to_pixelarray = sizeof(bitmap);
    pbitmap->bitmapinfoheader.dibheadersize = sizeof(bitmapinfoheader);
    pbitmap->bitmapinfoheader.width = _width;
    pbitmap->bitmapinfoheader.height = _height;
    pbitmap->bitmapinfoheader.planes = _planes;
    pbitmap->bitmapinfoheader.bitsperpixel = _bitsperpixel;
    pbitmap->bitmapinfoheader.compression = _compression;
    pbitmap->bitmapinfoheader.imagesize = _pixelbytesize;
    pbitmap->bitmapinfoheader.ypixelpermeter = _ypixelpermeter;
    pbitmap->bitmapinfoheader.xpixelpermeter = _xpixelpermeter;
    pbitmap->bitmapinfoheader.numcolorspallette = 0;
    fwrite(pbitmap, 1, sizeof(bitmap), fp);

    char codetest[(size + border * 2) * (size + border * 2)];

    int w = 0;
    for (int y = -border; y < size + border; y++) {
        for (int x = -border; x < size + border; x++) {
            //fputs((qrcodegen_getModule(qrcode, x, y) ? "# " : "  "), stdout);
            if (qrcodegen_getModule(qrcode, x, y)) {
                codetest[w] = 'n';
            } else {
                codetest[w] = 'b';
            }
            w++;
        }
    }
    w = 0;
    long long k = 0;
    for (int i = 0; i < (size + border * 2); i++) {
        for (int m = 0; m < (_height / (size + border * 2)); m++) {
            for (int j = 0; j < (size + border * 2); j++) {
                //printf("%c ",codetest[w]);

                if (codetest[w] == 'n') {
                    for (int n = 0; n < (_height / (size + border * 2)); n++) {
                        pixelbuffer[k] = 0x00;
                        pixelbuffer[k + 1] = 0x00;
                        pixelbuffer[k + 2] = 0x00;

                        k = k + 3;
                    }
                } else {
                    for (int n = 0; n < (_height / (size + border * 2)); n++) {
                        pixelbuffer[k] = 0xFF;
                        pixelbuffer[k + 1] = 0xFF;
                        pixelbuffer[k + 2] = 0xFF;

                        k = k + 3;
                    }
                }
                w++;
            }
            w = (size + border * 2) * i;
        }
        //fputs("\n", stdout);
    }

    fwrite(pixelbuffer, 1, _pixelbytesize, fp);

    fclose(fp);
    free(pbitmap);
    free(pixelbuffer);
}

int return_last_id() {
    char * request = NULL;
    int id;
    MYSQL_RES * result;
    request = malloc(sizeof(char) * 256);
    if(request == NULL) {
        printf("Allocation error");
        exit(0);
    }
    sprintf(request, "SELECT userID FROM useraccount ORDER BY userID DESC LIMIT 1");

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql, MYSQL_READ_DEFAULT_GROUP, "option");

    if (mysql_real_connect(&mysql, "localhost", "root", "", "mydb", 3306, NULL, 0)) {
        mysql_query(&mysql, request);
    }
    free(request);

    int num_fields = mysql_num_fields(result);
    MYSQL_ROW row;
    if(mysql_num_rows(result) > 0) {
        while ((row = mysql_fetch_row(result))) {
            for(int i = 0; i < num_fields; i++) {
                id = (int)row[i];
            }
        }
    }
    else {
        printf("\nERROR\n");
    }
    mysql_free_result(result);
    return atoi(id);
}

int main(int argc, char *argv[]) {

    GtkBuilder *gtkBuilder;
    GtkWidget *window_connect;
    GtkWidget *button1;
    GtkBox *box1;

    gtk_init(&argc, &argv);
    gtkBuilder = gtk_builder_new();
    gtk_builder_add_from_file(gtkBuilder, "gui.glade", NULL);

    window_connect = GTK_WIDGET(gtk_builder_get_object(gtkBuilder, "window_connect"));
    button1 = GTK_WIDGET(gtk_builder_get_object(gtkBuilder, "button1"));

    Inputs Input;
    Input.entry1 = gtk_builder_get_object(gtkBuilder, "entry1");
    Input.entry2 = gtk_builder_get_object(gtkBuilder, "entry2");
    Input.entry3 = gtk_builder_get_object(gtkBuilder, "entry3");
    Input.entry4 = gtk_builder_get_object(gtkBuilder, "entry4");
    Input.entry5 = gtk_builder_get_object(gtkBuilder, "entry5");
    Input.entry6 = gtk_builder_get_object(gtkBuilder, "entry6");
    Input.city = gtk_builder_get_object(gtkBuilder, "city");
    Input.checkbutton1 = gtk_builder_get_object(gtkBuilder, "checkbutton1");

    gtk_builder_connect_signals(gtkBuilder, NULL);
    g_signal_connect(G_OBJECT(window_connect), "destroy", (GCallback) on_window_connect_destroy, NULL);
    g_signal_connect(G_OBJECT(button1), "clicked", (GCallback) sign_in, &Input);
    g_object_unref(gtkBuilder);
    gtk_widget_show(window_connect);
    gtk_main();

    int id = 5;
    id = return_last_id();
    printf("id = %d",id);

    return 0;
}

void on_window_connect_destroy() {
    gtk_main_quit();
}
