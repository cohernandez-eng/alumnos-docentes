const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const morgan = require('morgan');
const { syncDatabase } = require('./models');

const app = express();
const PORT = process.env.PORT || 3000;


var corsOptions={
    origin: '*'
    ,method: 'GET,HEAD,PUT,PATCH,POST,DELETE'
    ,preflightContinue: false
    ,optionsSuccessStatus:200
}

// Middleware
app.use(cors(corsOptions));
app.use(morgan('combined'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Routes
app.use('/api/maestro', require('./routes/maestro_route'));
app.use('/api/alumno', require('./routes/alumno_route'));

// Routes new
app.use('/api/usuario',require('./routes/usuario_route'));
app.use('/api/rol',require('./routes/rol_route'));
app.use('/api/modulo',require('./routes/modulo_route'));
app.use('/api/tipoacceso',require('./routes/tipoacceso_route'));
app.use('/api/usuarioacceso',require('./routes/usuarioacceso_route'));

// Route PATRÓN PROTOTYPE
app.use('/api/clonacion', require('./routes/clonacion_route'));


//Ruta para empezar a verificar procesos, el puerto se otorga arriba y como es local localhost
//http://localhost:3000/
app.get("/", (req,res) => {
    res.json({"nombre":"Christopher Emmanuel Arellano Santizo ",
            "carnet":"2400201",
            "curso": "Progrmacion 4",
            "Edad": 26
            });
});


// Start server
app.listen(PORT, async () => {
  console.log(`Server running on port ${PORT}`);
  await syncDatabase();
});

module.exports = app;