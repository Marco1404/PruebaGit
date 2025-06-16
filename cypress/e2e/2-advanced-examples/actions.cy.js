describe('Prueba de agregar producto', () => {
  it('Debe permitir agregar un producto si el usuario está logueado', () => {
    cy.visit('http://localhost/tienda_web/login.php');  // URL del login de tu software

    // Completar formulario de login con un usuario de prueba
    cy.get('input[name="email"]').type('usuario@ejemplo.com');
    cy.get('input[name="password"]').type('contraseña123');  // Contraseña válida o de prueba
    cy.get('button[type="submit"]').click();

    // Verificar que el login fue exitoso, por ejemplo, asegurarse de que redirige a la página principal
    cy.url().should('include', '/home');  // Ajusta según la ruta de tu página principal

    // Proceder a realizar las acciones de la prueba
    cy.get('button#agregar-producto').click();
    cy.get('input#nombre-producto').type('Producto de prueba');
    cy.get('input#precio-producto').type('100');
    cy.get('button#confirmar-agregar').click();

    // Verificar que el producto se haya agregado correctamente
    cy.contains('Producto agregado exitosamente').should('be.visible');
  });
});
