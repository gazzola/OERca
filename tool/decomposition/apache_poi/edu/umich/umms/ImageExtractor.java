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

import java.io.File;
import edu.umich.umms.ImageExtractorPowerPoint;
import edu.umich.umms.ImageExtractorWordDocument;

public abstract class ImageExtractor {

	public abstract int extractImages();

	public static void  main(String[] args) {
		// This is a test function?
    	int code;
    	ImageExtractor xtractor = null;
    	
        if (args.length < 2) {
            System.err.println("Usage:");
            System.err.println("\tImageExtractor <input_file> <output_directory>");
            return;
        }

        boolean inFileExists = (new File(args[0])).exists();
        if (! inFileExists) {
        	System.err.println("Input file " + args[0] + " doesn't exist!");
        	return;
        }
        boolean outDirExists = (new File(args[1])).exists();
        if (! outDirExists) {
        	outDirExists = (new File(args[1])).mkdirs();
        	if (!outDirExists) {
        		System.err.println("Output directory " + args[1] + " doesn't exist, and could not be created!");
        		return;
        	}
        }
  
        code = -1;
        
        if (args[0].endsWith(".ppt") || args[0].endsWith(".PPT")) {
        	try {
        		xtractor = new ImageExtractorPowerPoint(args[0], args[1]);
        	} catch (Exception e) {
                System.err.println("Caught Exception: " +  e.getMessage());
        	}
        } else if (args[0].endsWith(".doc") || args[0].endsWith(".DOC")) {
        	try {
        		xtractor = new ImageExtractorWordDocument(args[0], args[1]);
        	} catch (Exception e) {
        		System.err.println("Caught Exception: " +  e.getMessage());
        	}
        } else {
        	System.err.println("The given input document type is not currently supported");
        }
        
        if (xtractor != null) {
        	code = xtractor.extractImages();
        	if (code != 0) {
        		System.out.println("Got return code " + code
        				+ " while processing file " + args[0]);
        	return;
        	}
        } else {
        	System.out.println("Unable to instantiate an extractor... for " + args[0]);
        }
	}
}
