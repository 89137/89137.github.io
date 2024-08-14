module.exports = async (req, res) => {
    if (req.method !== 'POST') {
      res.status(200).json({ message: 'API is working!' });
      return;
    }
  };
  