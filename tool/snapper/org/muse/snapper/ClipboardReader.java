/**********************************************************************************
 * $URL: https://source.sakaiproject.org/contrib/muse/snapper/trunk/applet/src/java/org/muse/snapper/ClipboardReader.java $
 * $Id: ClipboardReader.java 48911 2008-05-12 19:53:22Z ggolden@umich.edu $
 ***********************************************************************************
 *
 * Copyright (c) 2008 The Regents of the University of Michigan
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 ***********************************************************************************
 * For more information, contact Glenn R. Golden: ggolden@umich.edu
 **********************************************************************************/

package org.muse.snapper;

import java.awt.Dimension;
import java.awt.Image;
import java.awt.Toolkit;
import java.awt.datatransfer.Clipboard;
import java.awt.datatransfer.DataFlavor;
import java.awt.datatransfer.Transferable;
import java.awt.datatransfer.UnsupportedFlavorException;
import java.awt.image.ImageProducer;
import java.awt.image.PixelGrabber;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;

// import quicktime.QTSession;
// import quicktime.app.view.GraphicsImporterDrawer;
// import quicktime.app.view.QTImageProducer;
// import quicktime.qd.QDRect;
// import quicktime.std.image.GraphicsImporter;
// import quicktime.util.QTHandle;
// import quicktime.util.QTUtils;

/**
 * ClipboardReader reads text and images from the system clipboard.<br />
 * When used in an applet, the applet must be signed in order to gain the security clearance needed to access the system clipboard.
 */
public class ClipboardReader
{
	Image prevImage = null;

	/**
	 * Construct.
	 */
	public ClipboardReader()
	{
	}

	/**
	 * Clear the image from the clipboard.
	 * 
	 * @param clipboard
	 */
	public void clear()
	{
		// Note: clearing the clipboard in this way seems to block picking up a new image
		// until an application switch occurs -ggolden
		// Clipboard clipBoard = Toolkit.getDefaultToolkit().getSystemClipboard();
		// Transferable transferableText = new StringSelection("");
		// clipBoard.setContents(transferableText, null);
	}

	/**
	 * Read an image from the clipboard.
	 * 
	 * @return The image from the clipboard, or null if there is none.
	 */
	public Image getImage()
	{
		Clipboard clipBoard = Toolkit.getDefaultToolkit().getSystemClipboard();
		Transferable contents = clipBoard.getContents(null);

		if (contents == null) return null;

		DataFlavor[] flavors = contents.getTransferDataFlavors();
		DataFlavor pictFlavor = null;
		for (DataFlavor f : flavors)
		{
			if (f.getMimeType().startsWith("image/x-pict"))
			{
				pictFlavor = f;
				break;
			}
		}
		if ((pictFlavor != null) && !isQuickTimeAvailable()) pictFlavor = null;

		boolean isImage = contents.isDataFlavorSupported(DataFlavor.imageFlavor);

		if ((pictFlavor == null) && !isImage) return null;

		if (isImage)
		{
			try
			{
				Image rv = (Image) contents.getTransferData(DataFlavor.imageFlavor);
				// This is only useful when using auto-capture, and ties up a lot of memory
				// rv = detectChange(rv);
				return rv;
			}
			catch (Throwable e)
			{
			}
		}

		if (pictFlavor != null)
		{
			try
			{
				InputStream in = (InputStream) contents.getTransferData(pictFlavor);
				Image rv = readPict(in);
				// This is only useful when using auto-capture, and ties up a lot of memory
				// rv = detectChange(rv);
				return rv;
			}
			catch (UnsupportedFlavorException e)
			{
			}
			catch (IOException e)
			{
			}
			return null;
		}

		return null;
	}

	/**
	 * Read text from the clipboard.
	 * 
	 * @return The text on the clipboard, or null if there is none.
	 */
	public String getText()
	{
		Clipboard clipBoard = Toolkit.getDefaultToolkit().getSystemClipboard();
		Transferable contents = clipBoard.getContents(null);

		if (contents == null) return null;
		if (!contents.isDataFlavorSupported(DataFlavor.stringFlavor)) return null;

		try
		{
			String rv = (String) contents.getTransferData(DataFlavor.stringFlavor);
			return rv;
		}
		catch (UnsupportedFlavorException e)
		{
		}
		catch (IOException e)
		{
		}

		return null;
	}

	/**
	 * Check the current image against the previous - if the same, return null.
	 * 
	 * @param image
	 *        The current image
	 * @return The current image if different from the previous, or null if not different.
	 */
	protected Image detectChange(Image image)
	{
		// if no image, no change
		if (image == null) return null;

		// if no previous image, we have a change
		if (this.prevImage == null)
		{
			// record the current image for the next check
			this.prevImage = image;
			return image;
		}

		int width = image.getWidth(null);
		int height = image.getHeight(null);

		// if different size, we have a difference
		if ((width != this.prevImage.getWidth(null)) || (height != this.prevImage.getHeight(null)))
		{
			// record the current image for the next check
			this.prevImage = image;
			return image;
		}

		// check for a change in any row
		int[] currentPixels = new int[width];
		int[] previousPixels = new int[width];
		for (int row = 0; row < height; row++)
		{
			PixelGrabber currentPixelGrabber = new PixelGrabber(image, 0, row, width, 1, currentPixels, 0, width);
			PixelGrabber previousPixelGrabber = new PixelGrabber(this.prevImage, 0, row, width, 1, previousPixels, 0, width);

			try
			{
				currentPixelGrabber.grabPixels();
				previousPixelGrabber.grabPixels();

				for (int col = 0; col < width; col++)
				{
					// if a difference is detected
					if (currentPixels[col] != previousPixels[col])
					{
						// record the current image for the next check
						this.prevImage = image;
						return image;
					}
				}
			}
			catch (InterruptedException e)
			{
				System.err.println(e.toString());
			}
		}

		// no change
		return null;
	}

	/**
	 * Use quicktime to make an ImageProducer for the PICT<br />
	 * Using reflection to avoid compile-time quicktime dependency.
	 * 
	 * @param pictBytes
	 *        The bytes of the PICT
	 * @return The ImageProducer that will deliver the PICT.
	 */
	protected ImageProducer getPictProducer(byte[] pictBytes)
	{
		try
		{
			// use quick time to read the pict

			// if (QTSession.isInitialized() == false)
			if (!(Boolean) Class.forName("quicktime.QTSession").getMethod("isInitialized", (Class[]) null).invoke(null, (Object[]) null))
			{
				// QTSession.open();
				Class.forName("quicktime.QTSession").getMethod("open", (Class[]) null).invoke(null, (Object[]) null);
			}

			// GraphicsImporter importer = new GraphicsImporter(QTUtils.toOSType("PICT"));
			String PICT = "PICT";
			Integer type = (Integer) Class.forName("quicktime.util.QTUtils").getMethod("toOSType", new Class[] {PICT.getClass()}).invoke(null,
					new Object[] {PICT});
			Object importer = Class.forName("quicktime.std.image.GraphicsImporter").getConstructor(new Class[] {type.TYPE}).newInstance(
					new Object[] {type});

			// QTHandle pictHandle = new QTHandle(pictBytes);
			Object pictHandle = Class.forName("quicktime.util.QTHandle").getConstructor(new Class[] {pictBytes.getClass()}).newInstance(
					new Object[] {pictBytes});

			// importer.setDataHandle(pictHandle);
			Class.forName("quicktime.std.image.GraphicsImporter").getMethod("setDataHandle",
					new Class[] {Class.forName("quicktime.util.QTHandleRef")}).invoke(importer, new Object[] {pictHandle});

			// QDRect bounds = importer.getNaturalBounds();
			Object bounds = Class.forName("quicktime.std.image.GraphicsImporter").getMethod("getNaturalBounds", (Class[]) null).invoke(importer,
					(Object[]) null);

			// GraphicsImporterDrawer drawer = new GraphicsImporterDrawer(importer);
			Object drawer = Class.forName("quicktime.app.view.GraphicsImporterDrawer").getConstructor(new Class[] {importer.getClass()}).newInstance(
					new Object[] {importer});

			// Dimension dimension = new Dimension(bounds.getWidth(), bounds.getHeight());
			Integer width = (Integer) bounds.getClass().getMethod("getWidth", (Class[]) null).invoke(bounds, (Object[]) null);
			Integer height = (Integer) (Integer) bounds.getClass().getMethod("getHeight", (Class[]) null).invoke(bounds, (Object[]) null);
			Dimension dimension = new Dimension(width.intValue(), height.intValue());

			// ImageProducer producer = new QTImageProducer(drawer, dimension);
			Object producer = Class.forName("quicktime.app.view.QTImageProducer").getConstructor(
					new Class[] {drawer.getClass(), dimension.getClass()}).newInstance(new Object[] {drawer, dimension});

			return (ImageProducer) producer;
		}
		catch (Exception e)
		{
			return null;
		}
	}

	/**
	 * Use quicktime to make an ImageProducer for the PICT
	 * 
	 * @param pictBytes
	 *        The bytes of the PICT
	 * @return The ImageProducer that will deliver the PICT.
	 */
	/*
	 * protected ImageProducer getPictProducerQT(byte[] pictBytes) { try { // use quick time to read the pict if (QTSession.isInitialized() == false) {
	 * QTSession.open(); } GraphicsImporter importer = new GraphicsImporter(QTUtils.toOSType("PICT")); QTHandle pictHandle = new QTHandle(pictBytes);
	 * importer.setDataHandle(pictHandle); QDRect bounds = importer.getNaturalBounds(); GraphicsImporterDrawer drawer = new
	 * GraphicsImporterDrawer(importer); Dimension dimension = new Dimension(bounds.getWidth(), bounds.getHeight()); ImageProducer producer = new
	 * QTImageProducer(drawer, dimension); return producer; } catch (Exception e) { return null; } }
	 */

	/**
	 * Check if we have quicktime for java available.
	 * 
	 * @return true if quicktime is available, false if not.
	 */
	protected boolean isQuickTimeAvailable()
	{
		boolean rv = false;
		try
		{
			Class c = Class.forName("quicktime.QTSession");
			return true;
		}
		catch (Exception e)
		{
		}
		return false;
	}

	/**
	 * Read a PICT format image into an Image
	 * 
	 * @param pict
	 *        The PICT data stream.
	 * @return The Image.
	 */
	protected Image readPict(InputStream pict)
	{
		try
		{
			// read the data from the stream
			ByteArrayOutputStream out = new ByteArrayOutputStream();

			// leave room for the PICT 512 byte header
			byte[] header = new byte[512];
			out.write(header, 0, 512);

			// buffer the read
			byte[] buf = new byte[4 * 1024];
			int size = 0;
			while ((size = pict.read(buf, 0, 4096)) > 0)
			{
				out.write(buf, 0, size);
			}
			out.close();

			// in case we didn't get anything from the stream
			if (out.size() == 512) return null;

			byte[] pictBytes = out.toByteArray();

			// get an ImageProducer to read the pict
			ImageProducer producer = getPictProducer(pictBytes);

			return (Toolkit.getDefaultToolkit().createImage(producer));
		}
		catch (Exception e)
		{
		}
		return null;
	}
}
