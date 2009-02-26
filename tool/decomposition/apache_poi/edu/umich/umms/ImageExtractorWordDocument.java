/*
 * COPYRIGHT (c) 2009
 * The Regents of the University of Michigan
 * ALL RIGHTS RESERVED
 * 
 * Permission is granted to use, copy, create derivative works
 * and redistribute this software and such derivative works
 * for any purpose, so long as the name of The University of
 * Michigan is not used in any advertising or publicity
 * pertaining to the use of distribution of this software
 * without specific, written prior authorization.  If the
 * above copyright notice or any other identification of the
 * University of Michigan is included in any copy of any
 * portion of this software, then the disclaimer below must
 * also be included.
 * 
 * THIS SOFTWARE IS PROVIDED AS IS, WITHOUT REPRESENTATION
 * FROM THE UNIVERSITY OF MICHIGAN AS TO ITS FITNESS FOR ANY
 * PURPOSE, AND WITHOUT WARRANTY BY THE UNIVERSITY OF
 * MICHIGAN OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING
 * WITHOUT LIMITATION THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE
 * REGENTS OF THE UNIVERSITY OF MICHIGAN SHALL NOT BE LIABLE
 * FOR ANY DAMAGES, INCLUDING SPECIAL, INDIRECT, INCIDENTAL, OR
 * CONSEQUENTIAL DAMAGES, WITH RESPECT TO ANY CLAIM ARISING
 * OUT OF OR IN CONNECTION WITH THE USE OF THE SOFTWARE, EVEN
 * IF IT HAS BEEN OR IS HEREAFTER ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGES.
 */

package edu.umich.umms;

import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.List;

import org.apache.poi.hwpf.HWPFDocument;
import org.apache.poi.hwpf.usermodel.Picture;

public class ImageExtractorWordDocument extends ImageExtractor {

	String inFile = "";
	String outDir = "";
	HWPFDocument doc;
	
	public ImageExtractorWordDocument(String inFile, String outDir) {
		this.inFile = inFile;
		this.outDir = outDir;
		
		try {
			this.doc = new HWPFDocument(new FileInputStream(this.inFile));
		} catch (IOException e) {
	    	System.err.println(this.inFile + " doesn't appear to be a valid Word file!");
		}
		
	}

	public int extractImages() {
		int code = 0;
    	List allPics = this.doc.getPicturesTable().getAllPictures();
    	
    	//System.out.println("There are " + allPics.size() + " pictures in file " + this.inFile);

    	for (int i = 0; i < allPics.size(); i++) {

    		Picture pic = (Picture) allPics.get(i);
    		
    		byte[] data = pic.getContent();
    		String ext = pic.suggestFileExtension();

    		ext = "." + ext;
        	try {        		
        		FileOutputStream out = new FileOutputStream(outDir + "/image_" + (i+1) + ext);
        		out.write(data);
        		out.close();
        	} catch (IOException e) {
                System.err.println("Caught IOException: " +  e.getMessage());
        		code = 2;
        	}

    	}
     return code;
	}
}
