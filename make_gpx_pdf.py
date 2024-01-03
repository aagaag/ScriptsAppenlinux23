import gpxpy
import osmnx as ox
import matplotlib.pyplot as plt
from PIL import Image, ImageDraw
import io

def load_gpx(gpx_file_path):
    with open(gpx_file_path, 'r') as gpx_file:
        gpx = gpxpy.parse(gpx_file)
    return gpx

def get_route_coordinates(gpx):
    points = []
    for track in gpx.tracks:
        for segment in track.segments:
            for point in segment.points:
                points.append((point.latitude, point.longitude))
    return points

def get_map_tiles(route_coordinates, zoom=14):
    # Calculate the bounding box
    north, south, east, west = ox.utils_geo.bbox_from_points(route_coordinates)
    return ox.plot_graph_folium(ox.graph_from_bbox(north, south, east, west, network_type='bike'), zoom=zoom)

def draw_route_on_map(route_coordinates, map_tiles):
    # Convert GPS coordinates to pixel coordinates
    # Note: This step can be complex depending on the map projection used
    # For simplicity, this part is highly abstracted
    pixel_coordinates = convert_gps_to_pixels(route_coordinates, map_tiles)
    
    # Create an image from the map tiles
    map_image = Image.open(io.BytesIO(map_tiles))

    # Draw the route
    draw = ImageDraw.Draw(map_image)
    draw.line(pixel_coordinates, fill='blue', width=3)

    return map_image

def save_map_as_pdf(map_image, output_path):
    map_image.save(output_path, "PDF")

# Example Usage
gpx_file_path = 'path_to_your_gpx_file.gpx'
output_pdf_path = 'output_map.pdf'

gpx = load_gpx(gpx_file_path)
route_coordinates = get_route_coordinates(gpx)
map_tiles = get_map_tiles(route_coordinates)
map_with_route = draw_route_on_map(route_coordinates, map_tiles)
save_map_as_pdf(map_with_route, output_pdf_path)
