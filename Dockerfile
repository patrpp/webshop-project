#kiinduló image 
FROM node:20

 #nem root user létrehozása
RUN addgroup app && adduser --system --ingroup app app

#munkakönyvtár definiálása
WORKDIR /app
# A package.json és package-lock.json fájlok másolása
COPY frontend/package.json frontend/package-lock.json ./

#függőségek telepítése
RUN npm install

#fájlok másolása a konténerbe
COPY frontend/ .

#futtatási jogok beállítása a nem root usernek
RUN chown -R app:app /app
RUN chmod -R 755 /app

#átváltás a nem root userre
USER app

#konténer porjának megadása
EXPOSE 5173

#parancs a konténer indításához
CMD ["npm", "run", "dev"]
