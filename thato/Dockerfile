# Use official nginx web server image
FROM nginx:alpine

# Remove default nginx website
RUN rm -rf /usr/share/nginx/html/*

# Copy all project files into nginx web directory
COPY . /usr/share/nginx/html
