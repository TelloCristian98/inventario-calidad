const pdf = require("html-pdf");
const fs = require("fs");
const path = require("path");

// Ruta al archivo HTML
const htmlPath = path.join(__dirname, "index.html");
const html = fs.readFileSync(htmlPath, "utf8");

// Opciones para el PDF
const options = {
  format: "A4",
  orientation: "portrait",
  border: "10mm",
  base: `file://${path.dirname(htmlPath)}/`,
};

// Generar el PDF
pdf
  .create(html, options)
  .toFile(path.join(__dirname, "report.pdf"), (err, res) => {
    if (err) return console.log(err);
    console.log(res); // { filename: 'report.pdf' }
  });
