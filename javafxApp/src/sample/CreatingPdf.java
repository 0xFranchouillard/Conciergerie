package sample;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;
import org.apache.pdfbox.pdmodel.font.PDType1Font;

import java.io.File;
import java.io.IOException;

public class CreatingPdf {

    PDPageContentStream contentStream;
    PDDocument pdf = new PDDocument();

    public void createPdf(String filename, String message) throws IOException {

       addPage();

        float leading = 1.5f * 12;
        int lineCount = 0;

        contentStream.beginText();
        contentStream.setFont(PDType1Font.TIMES_ROMAN, 11);
        contentStream.newLineAtOffset(30, 760);


        String[] lines = message.split("\\n");


        for (int i = 0; i < lines.length ; i++) {

            String[] rowInfo = lines[i].split("  ");

            for (int j = 0; j < rowInfo.length  ; j++) {
                contentStream.showText( rowInfo[j]);
                contentStream.newLineAtOffset(0, - leading);
                lineCount++;

                if(lineCount >= 40){
                    closeStream();
                    addPage();
                    contentStream.beginText();
                    contentStream.setFont(PDType1Font.TIMES_ROMAN, 12);
                    contentStream.newLineAtOffset(30, 760);
                    lineCount = 0;
                    leading = 1.5f * 12;
                }
            }

            contentStream.newLineAtOffset(0, - leading);
            lineCount++;
            System.out.println(lines[i]);
        }


        closeStream();

        pdf.save(new File(filename));
        pdf.close();

    }


    private void addPage() throws IOException {

        PDPage page = new PDPage();
        pdf.addPage(page);
        contentStream = new PDPageContentStream(pdf, page);
    }


    private void closeStream() throws IOException {
        contentStream.endText();
        contentStream.close();
    }
}
