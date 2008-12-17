/**********************************************************************************
 * $URL: https://source.sakaiproject.org/contrib/muse/snapper/trunk/applet/src/java/org/muse/snapper/ImageCanvas.java $
 * $Id: ImageCanvas.java 48201 2008-04-19 23:40:43Z ggolden@umich.edu $
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

import java.awt.Canvas;
import java.awt.Color;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.MediaTracker;

/**
 * A java AWT Canvas that displays an image. The image is scaled to fit, preserving aspect ratio.
 */
public class ImageCanvas extends Canvas
{
	protected int height = 32;

	protected Image image = null;

	protected int width = 32;

	/**
	 * Construct.
	 */
	public ImageCanvas()
	{
		setBackground(Color.LIGHT_GRAY);
		setSize(this.width, this.height);
		setVisible(true);
	}

	/**
	 * Construct.
	 * 
	 * @param image
	 *        Use this image.
	 * @param width
	 *        The width.
	 * @param height
	 *        The height.
	 */
	public ImageCanvas(Image image, int width, int height)
	{
		this.width = width;
		this.height = height;

		setBackground(Color.LIGHT_GRAY);
		setSize(this.width, this.height);

		if (image != null)
		{
			setImage(image);
		}

		setVisible(true);
	}

	/**
	 * Access the image on the canvas.
	 * 
	 * @return The image on the canvas, or null if there is none.
	 */
	public Image getImage()
	{
		return this.image;
	}

	/**
	 * {@inheritDoc}
	 */
	public void paint(Graphics g)
	{
		if (this.image != null)
		{
			int imageWidth = image.getWidth(null);
			int imageHeight = image.getHeight(null);

			// if smaller than our size, draw it
			if ((imageWidth <= this.width) && (imageHeight <= this.height))
			{
				int x = (this.width - imageWidth) / 2;
				int y = (this.height - imageHeight) / 2;
				g.drawImage(this.image, x, y, null);
			}

			else
			{
				int w = this.width;
				int h = this.height;

				// preserve the aspect of the full image, not exceeding the thumb dimensions
				if (imageWidth > imageHeight)
				{
					// full width will take the full desired width, set the appropriate height
					h = (int) ((((float) w) / ((float) imageWidth)) * ((float) imageHeight));
				}
				else
				{
					// full height will take the full desired height, set the appropriate width
					w = (int) ((((float) h) / ((float) imageHeight)) * ((float) imageWidth));
				}

				int x = (this.width - w) / 2;
				int y = (this.height - h) / 2;

				g.drawImage(this.image, x, y, w, h, null);
			}
		}
	}

	/**
	 * Set the image to use on the canvas.
	 * 
	 * @param image
	 *        The image.
	 */
	public void setImage(Image image)
	{
		if (image == null)
		{
			this.image = null;
			return;
		}

		// get it loaded
		MediaTracker mediaTracker = new MediaTracker(this);
		mediaTracker.addImage(image, 0);
		try
		{
			mediaTracker.waitForID(0);
			this.image = image;
		}
		catch (InterruptedException ie)
		{
		}
	}
}
