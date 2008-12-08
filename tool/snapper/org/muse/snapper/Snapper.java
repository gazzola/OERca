/**********************************************************************************
 * $URL: https://source.sakaiproject.org/contrib/muse/snapper/trunk/applet/src/java/org/muse/snapper/Snapper.java $
 * $Id: Snapper.java 48682 2008-05-06 20:20:43Z ggolden@umich.edu $
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

import java.applet.Applet;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.datatransfer.Clipboard;
import java.awt.image.BufferedImage;
import java.io.BufferedOutputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.security.AccessController;
import java.security.PrivilegedAction;

import org.apache.commons.codec.binary.Base64;

import com.sun.image.codec.jpeg.JPEGCodec;
import com.sun.image.codec.jpeg.JPEGEncodeParam;
import com.sun.image.codec.jpeg.JPEGImageEncoder;

/**
 * Snapper displays an image from the system clipboard, and makes it available as a jpeg.
 */
public class Snapper extends Applet
{
	protected ImageCanvas canvas = null;

	protected ClipboardReader clipboard = null;

	protected String report = null;

	protected int height = 0;

	protected int width = 0;

	/**
	 * Check the system clipboard for an image; if there, pick it up.
	 * 
	 * @return true if there is a new image on the clipboard, false if not.
	 */
	public boolean checkClipboard()
	{
		try
		{
			// check the clipboard - as a privileged action
			final ClipboardReader cb = this.clipboard;
			Image image = (Image) AccessController.doPrivileged(new PrivilegedAction()
			{
				public Object run()
				{
					Image image = cb.getImage();
					return image;
				}
			});

//			Image image = this.clipboard.getImage();

			// if we got an image
			if (image != null)
			{
				this.report = "Size: " + image.getWidth(null) + " x " + image.getHeight(null);
				if ((image.getWidth(null) > this.width) || (image.getHeight(null) > this.height))
				{
					this.report += " (scaled)";
				}

				// update the image on our canvas
				this.canvas.setImage(image);
				this.canvas.repaint();
				return true;
			}

			this.report = "There is no new image on the clipboard";
			return false;
		}
		catch (Throwable t)
		{
			this.report = t.toString();
			return false;
		}
	}

	/**
	 * Access the clipboard status report.
	 * 
	 * @return The clipboard status report.
	 */
	public String getReport()
	{
		return this.report;
	}

	/**
	 * Clear the clipboard and the image from the display.
	 */
	public void clear()
	{
		// clear the clipboard
		this.clipboard.clear();

		this.report = "Cleared";

		this.canvas.setImage(null);
		this.canvas.repaint();
	}

	/**
	 * Get the current image encoded as a jpeg and further encoded as base64.
	 * 
	 * @return The string base64 encoding of the current image as a jpeg.
	 */
	public String getBase64Jpeg()
	{
		Image image = this.canvas.getImage();
		if (image == null) return null;
		try
		{
			int width = image.getWidth(null);
			int height = image.getHeight(null);

			// draw the scaled thumb
			BufferedImage thumbImage = new BufferedImage(width, height, BufferedImage.TYPE_INT_RGB);
			Graphics2D g2D = thumbImage.createGraphics();
			g2D.drawImage(image, 0, 0, width, height, null);

			// encode as jpeg to a byte array
			ByteArrayOutputStream byteStream = new ByteArrayOutputStream();
			BufferedOutputStream out = new BufferedOutputStream(byteStream);
			JPEGImageEncoder encoder = JPEGCodec.createJPEGEncoder(out);
			JPEGEncodeParam param = encoder.getDefaultJPEGEncodeParam(thumbImage);
			param.setQuality(100.0f, false);
			encoder.setJPEGEncodeParam(param);
			encoder.encode(thumbImage);
			out.close();
			byte[] thumb = byteStream.toByteArray();
			byteStream.reset();	// Make this eligible for garbage collection

			// encode to base64
			byte[] encoded = Base64.encodeBase64(thumb, false);
			thumb = null;		// Make this eligible for garbage collection
			String rv = new String(encoded, "UTF-8");
			encoded = null;		// Make this eligible for garbage collection
			return rv;
		}
		catch (IOException e)
		{
		}
		catch (Throwable t)
		{
			this.report = t.toString();
		}

		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public void init()
	{
		this.width = getSize().width;
		this.height = getSize().height;

		this.clipboard = new ClipboardReader();

		this.canvas = new ImageCanvas(null, this.width, this.height);
		add(this.canvas);

		showStatus("inited");
	}
}
