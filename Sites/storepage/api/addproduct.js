// api/addProduct.js
const mysql = require('mysql2/promise');

module.exports = async (req, res) => {
  if (req.method !== 'POST') {
    res.status(405).json({ message: 'Only POST requests allowed' });
    return;
  }

  // MySQL Database connection info (replace with your credentials)
  const connection = await mysql.createConnection({
    host: 'sql7.freemysqlhosting.net',
    user: 'sql7725987',
    password: 'iumvRQVX3V',
    database: 'sql7725987'
  });

  const { title, description, image_url, link } = req.body;

  if (!title || !description || !image_url || !link) {
    res.status(400).json({ message: 'All fields are required' });
    return;
  }

  try {
    // Insert product into database
    const [result] = await connection.execute(
      'INSERT INTO products (title, description, image_url, link) VALUES (?, ?, ?, ?)',
      [title, description, image_url, link]
    );

    res.status(200).json({ message: 'Product added successfully!', productId: result.insertId });
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Database error' });
  } finally {
    await connection.end();
  }
};
