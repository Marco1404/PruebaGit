describe('Prueba E2E de la tienda web', () => {
  
    it('Verifica la carga de la página de inicio', () => {
      cy.visit('http://localhost/tienda_web'); // Asegúrate de que la URL es correcta
      cy.title().should('include', 'Tienda Web'); // Verifica que el título sea correcto
    });
  
    it('Iniciar sesión con credenciales válidas', () => {
      // Abre la página de login
      cy.visit('http://localhost/tienda_web/login.php'); // Cambia la URL a la del login de tu app
  
      // Llena los campos de usuario y contraseña
      cy.get('input[name="username"]').type('usuario1'); // Reemplaza con el nombre del campo real
      cy.get('input[name="password"]').type('password123'); // Reemplaza con el nombre del campo real
  
      // Haz clic en el botón de inicio de sesión
      cy.get('button[type="submit"]').click();
  
      // Verifica que el usuario fue redirigido a la página principal
      cy.url().should('eq', 'http://localhost/tienda_web/index.php'); // Cambia la URL si es necesario
      cy.contains('Bienvenido, usuario1').should('be.visible'); // Verifica que el mensaje de bienvenida sea visible
    });
  
    it('Agrega un producto al carrito', () => {
      cy.visit('http://localhost/tienda_web/productos.php'); // Asegúrate de que esta URL sea correcta
  
      // Encuentra un producto específico y haz clic en él
      cy.contains('Producto 1').click(); // Asegúrate de que el nombre coincida exactamente
  
      // Haz clic en el botón "Agregar al carrito"
      cy.get('button').contains('Agregar al carrito').click();
  
      // Verifica que el carrito ahora tenga el producto
      cy.contains('Carrito').click(); // Dirígete al carrito
      cy.contains('Producto 1').should('be.visible'); // Verifica que el producto esté en el carrito
    });
  
    it('Verifica el proceso de pago', () => {
      cy.visit('http://localhost/tienda_web/carrito.php'); // Asegúrate de que la URL sea correcta
  
      // Verifica que el producto esté en el carrito
      cy.contains('Producto 1').should('be.visible');
  
      // Realiza el pago (asumiendo que tienes un formulario para esto)
      cy.get('input[name="direccion"]').type('Calle Falsa 123');
      cy.get('input[name="tarjeta"]').type('1234567812345678');
      cy.get('button[type="submit"]').contains('Pagar').click();
  
      // Verifica que se muestra el mensaje de éxito
      cy.contains('Gracias por tu compra').should('be.visible');
    });
  
  });