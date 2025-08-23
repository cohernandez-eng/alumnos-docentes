<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos de Universidad</title>
    <style>
        body { font-family: Arial; margin: 20px; background-color: #f4f4f4; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        form { margin-bottom: 20px; background: #fff; padding: 10px; border-radius: 5px; }
        input, select { padding: 5px; margin: 5px; }
        button { padding: 5px 10px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
        .delete-btn { background: #dc3545; }
        .delete-btn:hover { background: #c82333; }
    </style>
</head>
<body>

<h1>Datos de Universidad</h1>

<!-- Selección de entidad -->
<select id="entidadSelect">
    <option value="alumnos">Alumnos</option>
    <option value="docentes">Docentes</option>
</select>

<!-- Formulario de creación -->
<form id="createForm">
    <div id="formFields"></div>
    <button type="submit">Agregar</button>
</form>

<!-- Tabla de datos -->
<table id="dataTable">
    <thead id="tableHead"></thead>
    <tbody id="tableBody"></tbody>
</table>

<script>
const entidadSelect = document.getElementById('entidadSelect');
const formFields = document.getElementById('formFields');
const tableHead = document.getElementById('tableHead');
const tableBody = document.getElementById('tableBody');
const createForm = document.getElementById('createForm');

function loadFormFields() {
    formFields.innerHTML = '';
    if (entidadSelect.value === 'alumnos') {
        formFields.innerHTML = `
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="number" name="edad" placeholder="Edad" required>
            
            
        `;
       
       
    } else {
         (entidadSelect.value === 'docentes')
        formFields.innerHTML = `
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="materia" placeholder="Materia" required>
        `;
    }
}

function loadData() {
    fetch(`api.php?entidad=${entidadSelect.value}`)
        .then(res => res.json())
        .then(data => {
            tableHead.innerHTML = '';
            tableBody.innerHTML = '';

            if (data.length > 0) {
                const headers = Object.keys(data[0]);
                tableHead.innerHTML = `<tr>${headers.map(h => `<th>${h}</th>`).join('')}<th>Acciones</th></tr>`;

                data.forEach(row => {
                    tableBody.innerHTML += `
                        <tr>
                            ${headers.map(h => `<td>${row[h]}</td>`).join('')}
                            <td>
                                <button onclick="editData(${row.id}, '${row.nombre}', '${row.materia || row.edad || ''}')">Editar</button>
                                <button class="delete-btn" onclick="deleteData(${row.id})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableBody.innerHTML = `<tr><td colspan="5">No hay registros</td></tr>`;
            }
        });
}

let editingId = null;

function editData(id, nombre, extra) {
    editingId = id;
    formFields.innerHTML = '';

    if (entidadSelect.value === 'alumnos') {
        formFields.innerHTML = `
            <input type="text" name="nombre" value="${nombre}" required>
            <input type="number" name="edad" value="${extra}" required>
            
            
        `;
    } else {
        formFields.innerHTML = `
            <input type="text" name="nombre" value="${nombre}" required>
            <input type="text" name="materia" value="${extra}" required>
        `;
        
    }
}

createForm.addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(createForm);
    const jsonData = {};
    formData.forEach((v, k) => jsonData[k] = v);

    let method = editingId ? 'PUT' : 'POST';
    let url = `api.php?entidad=${entidadSelect.value}` + (editingId ? `&id=${editingId}` : '');

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(jsonData)
    }).then(() => {
        createForm.reset();
        editingId = null;
        loadFormFields();
        loadData();
    });
});


entidadSelect.addEventListener('change', () => {
    loadFormFields();
    loadData();
});

loadFormFields();
loadData();

</script>

</body>
</html>
