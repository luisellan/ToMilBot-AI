using Azure;
using FermaOrders.API.Controllers.Response;
using FermaOrders.Application.Dto.Application;
using FermaOrders.Application.Dto.Components.Customer;
using FermaOrders.Application.Interface.Application;
using FermaOrders.Domain.Entities;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using System.Net;

namespace FermaOrders.API.Controllers.Application
{
    [Route("api/[controller]")]
    [ApiController]
    public class OrdersController : ControllerBase
    {
        private readonly IOrderService _orderService;

        public OrdersController(IOrderService orderService)
        {
            _orderService = orderService;
        }

        [Authorize(Roles = "Admin,Empleado")]
        [HttpPost]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status403Forbidden)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> CreateOrderAsync([FromBody] OrderDto orderDto)
        {
            var response = new RespuestaAPI();
            try
            {
                if (orderDto == null)
                {
                    return BadRequest("Invalid order data.");
                }

                var order = await _orderService.CreateOrderAsync(orderDto); // Aquí el await
                response.Result = order;
                response.IsSuccess = true;
                response.StatusCode = HttpStatusCode.OK;
                return Ok(response);
            }
            catch (Exception ex)
            {
                response.StatusCode = HttpStatusCode.InternalServerError;
                response.IsSuccess = false;
                response.ErrorMessages.Add($"Error: {ex.Message}");
                return StatusCode(500, response);
            }
        }

        [Authorize(Roles = "Admin,Empleado")]
        [HttpGet("orderitems/{orderId}")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status403Forbidden)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> GetOrderItemsByOrderIdAsync(int orderId)
        {
            var respuesta = new RespuestaAPI();
            try
            {
                var items = await _orderService.GetOrderItemByOrderAsync(orderId);

                respuesta.StatusCode = HttpStatusCode.OK;
                respuesta.IsSuccess = true;
                respuesta.Result = items;

                return Ok(respuesta);
            }
            catch (KeyNotFoundException ex)
            {
                respuesta.StatusCode = HttpStatusCode.NotFound;
                respuesta.IsSuccess = false;
                respuesta.ErrorMessages.Add($"Error: {ex.Message}");
                return NotFound(respuesta);
            }
            catch (Exception ex)
            {
                respuesta.StatusCode = HttpStatusCode.InternalServerError;
                respuesta.IsSuccess = false;
                respuesta.ErrorMessages.Add($"Error: {ex.Message}");
                return StatusCode(500, respuesta);
            }
        }


        [Authorize(Roles = "Admin,Empleado")]
        [HttpGet("customers/{customerId}")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status403Forbidden)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> ListOrderByCustomer(int customerId)
        {
            var respuesta = new RespuestaAPI();
            try
            {
                var orders = await _orderService.ListOrderByCustomer(customerId);

                respuesta.StatusCode = HttpStatusCode.OK;
                respuesta.IsSuccess = true;
                respuesta.Result = orders;

                return Ok(respuesta);
            }
            catch (KeyNotFoundException ex)
            {
                respuesta.StatusCode = HttpStatusCode.NotFound;
                respuesta.IsSuccess = false;
                respuesta.ErrorMessages.Add($"Error: {ex.Message}");
                return NotFound(respuesta);
            }
            catch (Exception ex)
            {
                respuesta.StatusCode = HttpStatusCode.InternalServerError;
                respuesta.IsSuccess = false;
                respuesta.ErrorMessages.Add($"Error: {ex.Message}");
                return StatusCode(500, respuesta);
            }
        }
    }
}
