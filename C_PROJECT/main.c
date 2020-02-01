#include <gtk/gtk.h>
#include <stdio.h>
#include <stdlib.h>
#include <winsock.h>
#include <MYSQL/mysql.h>
#include <stdbool.h>
#include <stddef.h>
#include <stdint.h>
#include <malloc.h>
#include "qrcodegen.h"
#include "qrcodegen.c"

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
    gpointer checkbutton1;
};

#pragma pack(pop)

void on_window_connect_destroy();

static void doBasicDemo(char *mail, int id);

static void printQr(const uint8_t qrcode[]);

void sign_in(GtkWidget *entry, Inputs *In) {
    char *log = gtk_entry_get_text(GTK_ENTRY(In->entry1));
    char *passwd = gtk_entry_get_text(GTK_ENTRY((*In).entry2));
    bool adm = gtk_toggle_button_get_active(In->checkbutton1);
    printf("%s\n%s\n%d\n", log, passwd, adm);
    char *request = (char *) malloc(256);
    int id = 1;

    sprintf(request, "INSERT INTO utilisateur(idUtilisateur,nom,email,qrCode) VALUES ('%d','%s','%s','%s')", id, log,
            log, passwd);

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql, MYSQL_READ_DEFAULT_GROUP, "option");
    if (mysql_real_connect(&mysql, "localhost", "root", "", "mydb", 3306, NULL, 0)) {
        mysql_query(&mysql, request);
        doBasicDemo(log, id);
    } else { printf("non ok "); }
}

static void doBasicDemo(char *mail, int id) {
    char *value = (char *) malloc(256);
    sprintf(value, "https://51.77.221.39/verif.php?mail='%s'&id='%d'", mail, id);
    enum qrcodegen_Ecc errCorLvl = qrcodegen_Ecc_LOW;  // Error correction level

    // Make and print the QR Code symbol
    uint8_t qrcode[qrcodegen_BUFFER_LEN_MAX];
    uint8_t tempBuffer[qrcodegen_BUFFER_LEN_MAX];
    bool ok = qrcodegen_encodeText(value, tempBuffer, qrcode, errCorLvl,
                                   qrcodegen_VERSION_MIN, qrcodegen_VERSION_MAX, qrcodegen_Mask_AUTO, true);
    if (ok)
        printQr(qrcode);
}

static void printQr(const uint8_t qrcode[]) {

    int size = qrcodegen_getSize(qrcode);
    int border = 2;

    FILE *fp = fopen("QrCode.bmp", "wb");
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
    Input.checkbutton1 = gtk_builder_get_object(gtkBuilder, "checkbutton1");

    gtk_builder_connect_signals(gtkBuilder, NULL);
    g_signal_connect(G_OBJECT(window_connect), "destroy", (GCallback) on_window_connect_destroy, NULL);
    g_signal_connect(G_OBJECT(button1), "clicked", (GCallback) sign_in, &Input);
    g_object_unref(gtkBuilder);
    gtk_widget_show(window_connect);
    gtk_main();
    return 0;
}

void on_window_connect_destroy() {
    gtk_main_quit();
}

