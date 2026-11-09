<?php
namespace Sexclick\Integration;
/** Internal interface, NOT the supplier's API specification. */
interface Gateway {
 /** Full/incremental catalogue including identifiers, variants, images and locales. */
 public function catalogue(?string $cursor = null): array;
 public function availability(array $supplierSkus): array;
 public function shippingQuotes(array $destination, array $items): array;
 /** Idempotency must be implemented according to provider-supported semantics. */
 public function placeOrder(array $paidOrder, string $idempotencyKey): array;
 public function findOrder(string $merchantReference): ?array;
 public function tracking(string $supplierOrderId): array;
 public function capabilities(): array;
}
final class UnconfiguredGateway implements Gateway {
 private function unavailable(): never {throw new \LogicException('Dreamlove: documentação, credenciais e adaptador ainda não configurados.');}
 public function catalogue(?string $cursor = null): array {$this->unavailable();}
 public function availability(array $supplierSkus): array {$this->unavailable();}
 public function shippingQuotes(array $destination, array $items): array {$this->unavailable();}
 public function placeOrder(array $paidOrder,string $idempotencyKey): array {$this->unavailable();}
 public function findOrder(string $merchantReference): ?array {$this->unavailable();}
 public function tracking(string $supplierOrderId): array {$this->unavailable();}
 public function capabilities(): array {return ['catalogue'=>false,'stock'=>false,'orders'=>false,'shipping'=>false,'tracking'=>false];}
}
