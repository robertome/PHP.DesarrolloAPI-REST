"# PHP.DesarrolloAPI-REST" 

Para ver especificación API

http://localhost:8000/api-docs/index.html

Para la consulta de resultados de usuario ya que no se ha incluido identificación por sesión, se ha redefinido el API para que soporte la operacion GETc sobre el PATH /api/v1/users/{userId}/results

En el desarrollo del API se ha utilizado Manejador de Excepciontes (mediante subscripcion kernel de eventos de Symfony).
También se han añadido algunos test para los Controladores
