import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Post,
  Put,
  UseGuards,
} from '@nestjs/common';
import { SaunalogService } from './saunalog.service';
import { CreateSaunalogDto } from 'src/dto/create-saunalog.dto';
import { Saunalog } from 'src/entities/saunalog.entity';
import { JwtAuthGuard } from 'src/auth/guards/jwt-auth.guard';
import { Request } from '@nestjs/common';
import { GetUser } from 'src/auth/decorator/get-user.decorator';
import { User } from 'src/entities/user.entity';



@Controller('saunalog')
@UseGuards(JwtAuthGuard)
export class SaunalogController {
  constructor(private readonly saunalogService: SaunalogService) {}

  @Get()
  getData() {
    return this.saunalogService.get();
  }

  @Get(':id')
  async getOneSaunalog(@Param('id', ParseIntPipe) id: number) {
    return await this.saunalogService.findOne(id);
  }

  @Post()
    postData(@Body() CreateSaunalogDto: CreateSaunalogDto, @GetUser() user: User) {
      return this.saunalogService.create(CreateSaunalogDto, user);
    }

  @Put(':id')
  update(
    @Param('id', ParseIntPipe) id: number,
    @Body('name') name: string,
    @Body('area') area: string,
    @Body('rank') rank: number,
    @Body('comment') comment: string,
  ) {
    return this.saunalogService.update(id, name, area, rank, comment);
  }

  @Delete(':id')
  delete(@Param('id', ParseIntPipe) id: number) {
    return this.saunalogService.delete(id);
  }
}
