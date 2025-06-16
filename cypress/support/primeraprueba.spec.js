describe('Mi primera prueba con Cypress', () => {
  it('Visita la página de inicio y verifica el título', () => {
    cy.visit('http://localhost/tienda_web');
    cy.title().should('include', 'Tienda Web'); 
  });
});