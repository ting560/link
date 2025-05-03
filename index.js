app.get("/proxy", async (req, res) => {
  try {
    const { url, password, action } = req.query;
    
    if (!url || !password || !action) {
      return res.status(400).send("Missing parameters");
    }

    // Extrair a base da URL (sem query params)
    const urlObj = new URL(url);
    const baseUrl = `${urlObj.origin}${urlObj.pathname}`;

    // Criar parâmetros de forma segura
    const params = new URLSearchParams();
    params.append('username', urlObj.searchParams.get('username') || '');
    params.append('password', password);
    params.append('action', action);

    const fullUrl = `${baseUrl}?${params.toString()}`;

    console.log(`URL correta:`, fullUrl);
    
    const response = await axios.get(fullUrl, {
      headers: {
        "User-Agent": "Mozilla/5.0 (...)"
      }
    });
    
    res.json(response.data);
  } catch (error) {
    // Tratamento de erro mantido
  }
});
