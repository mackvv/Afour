const mysql = require('mysql');

module.exports = async function (context, req) {
    const connection = mysql.createConnection({
        host: process.env.a4.database.windows.net,
        user: process.env.A4,
        password: process.env.Test1234!,
        database: process.env.A4
    });

    connection.connect();

    if (req.method === 'POST') {
        const allergenName = req.query.allergen_name;
        const allergyRecord = req.body;


        connection.query('DELETE FROM PatientAllergies WHERE allergen_name = ?', [allergenName], (error) => {
            if (error) {
                context.res = { status: 500, body: "Error deleting records: " + error };
                connection.end();
                return;
            }

            const query = 'INSERT INTO PatientAllergies (patient_name, allergen_name, reaction_description, severity_level) VALUES (?, ?, ?, ?)';
            const { patient_name, allergen_name, reaction_description, severity_level } = allergyRecord;
            connection.query(query, [patient_name, allergen_name, reaction_description, severity_level], (error) => {
                if (error) {
                    context.res = { status: 500, body: "Error inserting record: " + error };
                } else {
                    context.res = { status: 200, body: "Success." };
                }
                connection.end();
            });
        });
    } 
};
